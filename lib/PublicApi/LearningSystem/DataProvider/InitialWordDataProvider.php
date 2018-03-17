<?php

namespace PublicApi\LearningSystem\DataProvider;

use BlueDot\BlueDot;
use LearningSystem\Library\DataProviderInterface;
use LearningSystem\Library\ProvidedDataInterface;
use Library\Infrastructure\BlueDot\BaseBlueDotRepository;
use PublicApi\Language\Infrastructure\LanguageProvider;
use PublicApi\LearningSystem\DataProvider\Word\ProvidedWordDataCollection;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;

class InitialWordDataProvider extends BaseBlueDotRepository implements DataProviderInterface
{
    /**
     * @var LanguageProvider $languageProvider
     */
    private $languageProvider;
    /**
     * @var LearningUserProvider $learningUserProvider
     */
    private $learningUserProvider;
    /**
     * WordDataProvider constructor.
     * @param BlueDot $blueDot
     * @param string $apiName
     * @param LanguageProvider $languageProvider
     * @param LearningUserProvider $learningUserProvider
     */
    public function __construct(
        BlueDot $blueDot,
        string $apiName,
        LanguageProvider $languageProvider,
        LearningUserProvider $learningUserProvider
    ) {
        parent::__construct($blueDot, $apiName);

        $this->languageProvider = $languageProvider;
        $this->learningUserProvider = $learningUserProvider;
    }
    /**
     * @inheritdoc
     */
    public function getData(array $rules): ProvidedDataInterface
    {
        $this->blueDot->useRepository('learning_user_metadata');

        $initialWords = $this->getWordsFromLessons($rules['word_level']);

        $wordNumber = $this->getWordNumber($rules['word_number'], count($initialWords['words']));
        $wordIds = (empty($initialWords['words'])) ?
            $this->getWordsIds($this->languageProvider->getLanguage()->getId(), $rules['word_level']) :
            $this->getWordsIdsWithExcludedLesson(
                $this->languageProvider->getLanguage()->getId(),
                $initialWords['lesson_id'],
                $rules['word_level']
            );

        $restOfTheWords = $this->getRestOfTheWords($wordNumber, $wordIds, $rules['word_level']);

        $finalWords = array_merge($initialWords['words'], $restOfTheWords);

        return new ProvidedWordDataCollection($finalWords);
    }
    /**
     * @param array $finalWords
     * @param array $falseTranslations
     * @return ProvidedDataInterface
     */
    public function assignTranslationsAndGetFinalWordsAsDataCollection(
        array $finalWords,
        array $falseTranslations
    ): ProvidedDataInterface {
        foreach ($finalWords as &$finalWord) {
            $randomTranslations = array_rand($falseTranslations, 3);

            foreach ($randomTranslations as $randomTranslation) {
                $finalWord['false_translations'][] = $falseTranslations[$randomTranslation];

                unset($falseTranslations[$randomTranslation]);
            }
        }

        return new ProvidedWordDataCollection($finalWords);
    }
    /**
     * @param array $wordIds
     * @return array
     */
    public function getFalseTranslations(array $wordIds): array
    {
        $wordNumber = count($wordIds);
        $expectedLimit = $wordNumber * 3;

        $sql = sprintf(
            'SELECT DISTINCT t.name AS translations FROM word_translations AS t INNER JOIN words AS w ON w.id = t.word_id WHERE w.id NOT IN(%s) AND w.language_id = %d LIMIT %d',
            implode(',', $wordIds),
            $this->languageProvider->getLanguage()->getId(),
            $expectedLimit
        );

        $promise = $this->blueDot
            ->createStatementBuilder()
            ->addSql($sql)
            ->execute();

        $result = $promise->getResult()->extractColumn('translations')['translations'];

        if (count($result) !== $expectedLimit) {
            $message = sprintf(
                'Expected limit for false translation in initial game creation not reached. Expected limit: %d; Reached limit: %d; Word count: %d',
                $expectedLimit,
                count($result),
                $wordNumber
            );

            throw new \RuntimeException($message);
        }

        return $result;
    }
    /**
     * @param int $wordLevel
     * @return array
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     */
    private function getWordsFromLessons(int $wordLevel): array
    {
        $promise = $this->blueDot->execute('scenario.initial_data_collection', [
            'find_learning_lesson' => [
                'learning_user_id' => $this->learningUserProvider->getLearningUser()->getId(),
            ],
            'find_learning_lesson_words' => [
                'language_id' => $this->languageProvider->getLanguage()->getId(),
                'word_level' => $wordLevel,
            ],
        ]);

        $words = $promise->getResult()->get('find_learning_lesson_words')->toArray();
        unset($words['row_count']);

        $lessonId = $promise->getResult()->get('find_learning_lesson')->normalizeIfOneExists()->get('lesson_id');

        return [
            'words' => $words,
            'lesson_id' => (int) $lessonId,
        ];
    }
    /**
     * @param int $languageId
     * @param int $wordLevel
     * @return array
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     */
    private function getWordsIds(int $languageId, int $wordLevel): array
    {
        $promise = $this->blueDot->execute('simple.select.get_words_count', [
            'language_id' => $languageId,
            'word_level' => $wordLevel,
        ]);

        $result = $promise->getResult()->extractColumn('id')['id'];

        return $result;
    }
    /**
     * @param int $languageId
     * @param int $lessonId
     * @param int $wordLevel
     * @return array
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\EntityException
     */
    private function getWordsIdsWithExcludedLesson(
        int $languageId,
        int $lessonId,
        int $wordLevel
    ): array {
        $promise = $this->blueDot->execute('simple.select.get_words_count_with_lesson_excluded', [
            'language_id' => $languageId,
            'lesson_id' => $lessonId,
            'word_level' => $wordLevel,
        ]);

        $result = $promise->getResult()->extractColumn('id')['id'];

        return $result;
    }
    /**
     * @param int $wordNumber
     * @param array $wordIds
     * @param int $wordLevel
     * @return array
     * @throws \BlueDot\Exception\ConnectionException
     */
    private function getRestOfTheWords(
        int $wordNumber,
        array $wordIds,
        int $wordLevel
    ): array {

        $randomizedWordIds = $this->randomizeWordIds($wordIds, $wordNumber);

        if (count($randomizedWordIds) !== $wordNumber) {
            $message = sprintf(
                'Invalid number of words found. There has to be %d words. %d given',
                $wordNumber,
                count($wordIds)
            );

            throw new \RuntimeException($message);
        }

        $sql = sprintf(
            'SELECT w.id, w.level FROM words AS w WHERE w.language_id = %d AND w.level = %d AND w.id IN(%s) ',
            $this->languageProvider->getLanguage()->getId(),
            $wordLevel,
            implode(',', $randomizedWordIds)
        );

        $promise = $this->blueDot
            ->createStatementBuilder()
            ->addSql($sql)
            ->execute();

        return $promise->getResult()->toArray();
    }
    /**
     * @param int $rulesWordNumber
     * @param int $wordFromLessonsCount
     * @return int
     */
    private function getWordNumber(int $rulesWordNumber, int $wordFromLessonsCount): int
    {
        return $rulesWordNumber - $wordFromLessonsCount;
    }
    /**
     * @param array $finalWords
     * @return array
     */
    private function extractWordIds(array $finalWords): array
    {
        $wordIds = [];
        foreach ($finalWords as $finalWord) {
            $wordIds[] = $finalWord['id'];
        }

        return $wordIds;
    }
    /**
     * @param array $wordIds
     * @param int $wordNumber
     * @return array
     */
    private function randomizeWordIds(array $wordIds, int $wordNumber): array
    {
        $randomizedWordIdsKeys = array_rand($wordIds, $wordNumber);

        $randomizedWordIds = [];
        foreach ($randomizedWordIdsKeys as $randomizedWordIdsKey) {
            $randomizedWordIds[] = $wordIds[$randomizedWordIdsKey];
        }

        return $randomizedWordIds;
    }
}