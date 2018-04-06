<?php

namespace PublicApi\LearningSystem\Repository;

use Library\Infrastructure\BlueDot\BaseBlueDotRepository;

class WordDataRepository extends BaseBlueDotRepository
{
    /**
     * @param int $learningMetadataId
     * @param int $wordLevel
     * @param int $learningUserId
     * @param int $languageId
     * @return array
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function getWordsFromLessons(
        int $learningMetadataId,
        int $wordLevel,
        int $learningUserId,
        int $languageId
    ): array {
        $promise = $this->blueDot->execute('scenario.initial_data_collection', [
            'find_learning_lesson' => [
                'learning_metadata_id' => $learningMetadataId,
                'learning_user_id' => $learningUserId,
            ],
            'find_learning_lesson_words' => [
                'language_id' => $languageId,
                'word_level' => $wordLevel,
            ],
        ]);

        $words = $promise->getResult()->get('find_learning_lesson_words')['data'];

        $lessonId = $promise->getResult()->get('find_learning_lesson')['data'][0]['lesson_id'];

        return [
            'words' => $words,
            'lesson_id' => (int) $lessonId,
        ];
    }
    /**
     * @param int $languageId
     * @param int $wordLevel
     * @return array
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function getWordsIds(int $languageId, int $wordLevel): array
    {
        $promise = $this->blueDot->execute('simple.select.get_words_count', [
            'language_id' => $languageId,
            'word_level' => $wordLevel,
        ]);

        $result = $promise->getResult()->extractColumn('id')['data']['id'];

        return $result;
    }
    /**
     * @param int $languageId
     * @param int $lessonId
     * @param int $wordLevel
     * @return array
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function getWordsIdsWithExcludedLesson(
        int $languageId,
        int $lessonId,
        int $wordLevel
    ): array {
        $promise = $this->blueDot->execute('simple.select.get_words_count_with_lesson_excluded', [
            'language_id' => $languageId,
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
     * @param int $languageId
     * @return array
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function getRestOfTheWords(
        int $wordNumber,
        array $wordIds,
        int $wordLevel,
        int $languageId
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
            $languageId,
            $wordLevel,
            implode(',', $randomizedWordIds)
        );

        $promise = $this->blueDot
            ->createStatementBuilder()
            ->addSql($sql)
            ->execute();

        return $promise->getResult()['data'];
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