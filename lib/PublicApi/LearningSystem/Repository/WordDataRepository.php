<?php

namespace PublicApi\LearningSystem\Repository;

use BlueDot\BlueDot;
use Library\Infrastructure\BlueDot\BaseBlueDotRepository;
use PublicApi\Infrastructure\Communication\RepositoryCommunicator;
use PublicApiBundle\Entity\LearningLesson;
use PublicApi\Infrastructure\Model\Language;

class WordDataRepository extends BaseBlueDotRepository
{
    /**
     * @var RepositoryCommunicator $repositoryCommunicator
     */
    private $repositoryCommunicator;
    /**
     * WordDataRepository constructor.
     * @param BlueDot $blueDot
     * @param $apiName
     * @param RepositoryCommunicator $repositoryCommunicator
     */
    public function __construct(
        BlueDot $blueDot,
        $apiName,
        RepositoryCommunicator $repositoryCommunicator
    ) {
        parent::__construct($blueDot, $apiName);

        $this->repositoryCommunicator = $repositoryCommunicator;
    }
    /**
     * @param LearningLesson $learningLesson
     * @param Language $language
     * @param int $wordLevel
     * @return array
     */
    public function getWordsFromLessons(
        LearningLesson $learningLesson,
        Language $language,
        int $wordLevel
    ): array {
        return $this->repositoryCommunicator->getWordsByLevelAndLesson(
            $language,
            $learningLesson->getLessonObject(),
            $wordLevel
        );
    }
    /**
     * @param Language $language
     * @param int $wordLevel
     * @return array
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\EntityException
     */
    public function getWordsIds(Language $language, int $wordLevel): array
    {
        $promise = $this->blueDot->execute('simple.select.get_words_count', [
            'language_id' => $language->getId(),
            'word_level' => $wordLevel,
        ]);

        $result = $promise->getResult()->extractColumn('id')['data']['id'];

        return $result;
    }
    /**
     * @param Language $language
     * @param int $lessonId
     * @param int $wordLevel
     * @return array
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\EntityException
     * @throws \BlueDot\Exception\RepositoryException
     */
    public function getWordsIdsWithExcludedLesson(
        Language $language,
        int $lessonId,
        int $wordLevel
    ): array {

        $this->blueDot->useRepository('learning_user_metadata');

        $promise = $this->blueDot->execute('simple.select.get_words_count_with_lesson_excluded', [
            'language_id' => $language->getId(),
            'lesson_id' => $lessonId,
            'word_level' => $wordLevel,
        ]);

        $result = $promise->getResult()->extractColumn('id')['data']['id'];

        return $result;
    }
    /**
     * @param int $wordNumber
     * @param array $wordIds
     * @param int $wordLevel
     * @param Language $language
     * @return array
     */
    public function getRestOfTheWords(
        int $wordNumber,
        array $wordIds,
        int $wordLevel,
        Language $language
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

        return $this->repositoryCommunicator->getWordsByLevelAndLessonByExactIds(
            $language,
            $wordLevel,
            $randomizedWordIds
        );
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