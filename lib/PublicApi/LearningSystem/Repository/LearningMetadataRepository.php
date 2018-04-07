<?php

namespace PublicApi\LearningSystem\Repository;

use BlueDot\Entity\Entity;
use BlueDot\Entity\PromiseInterface;
use Library\Infrastructure\BlueDot\BaseBlueDotRepository;

class LearningMetadataRepository extends BaseBlueDotRepository
{
    /**
     * @param int $languageId
     * @param int $learningUserId
     * @return array
     */
    public function createLearningMetadata(
        int $languageId,
        int $learningUserId
    ): array
    {
        $this->blueDot->useRepository($this->apiName);

        return $this->doCreateLearningMetadata(
            $languageId,
            $learningUserId
        );
    }
    /**
     * @param int $learningUserId
     * @param int $languageId
     * @return array
     */
    private function doCreateLearningMetadata(
        int $languageId,
        int $learningUserId
    ): array {
        $lessonIds = $this->blueDot
            ->execute('simple.select.get_lesson_ids', [
                'language_id' => $languageId,
            ])
            ->getResult()
            ->toArray()['data']['id'];

        foreach ($lessonIds as $lessonId) {
            $this->blueDot->prepareExecution(
                'scenario.create_learning_metadata', [
                'create_learning_lesson' => [
                    'lesson_id' => $lessonId,
                ],
                'create_learning_metadata' => [
                    'learning_user_id' => $learningUserId,
                ],
            ]);
        }

        /** @var PromiseInterface[] $createLearningMetadataPromises */
        $createLearningMetadataPromises = $this->blueDot->executePrepared();

        /** @var PromiseInterface $promise */
        foreach ($createLearningMetadataPromises as $promise) {
            if ($promise->isSuccess()) {
                $learningMetadataId = $promise->getResult()->get('create_learning_metadata')['last_insert_id'];
                $learningLessonId = $promise->getResult()->get('create_learning_lesson')['last_insert_id'];

                $this->blueDot->execute('simple.update.update_learning_lesson', [
                    'learning_metadata_id' => $learningMetadataId,
                    'learning_lesson_id' => $learningLessonId,
                ]);
            }
        }

        $this->blueDot->executePrepared();

        /** @var Entity $result */
        $result = $createLearningMetadataPromises[0]->getResult();

        return [
            'learningMetadataId' => (int) $result->get('create_learning_metadata')['last_insert_id'],
        ];
    }
    /**
     * @param int $learningUserId
     * @param int $languageId
     * @return array
     */
    public function getLearningLessonPresentation(
        int $learningUserId,
        int $languageId
    ): array
    {
        if (!$this->blueDot->repository()->isCurrentlyUsingRepository('presentation')) {
            $this->blueDot->useRepository('presentation');
        }

        return $this->blueDot->execute('service.learning_lesson_presentation', [
            'learning_user_id' => $learningUserId,
            'language_id' => $languageId,
        ])->getResult()->get('data');
    }
    /**
     * @param int $learningUserId
     * @return array
     */
    public function getLearningGamesPresentation(
        int $learningUserId
    ): array {
        if (!$this->blueDot->repository()->isCurrentlyUsingRepository('presentation')) {
            $this->blueDot->useRepository('presentation');
        }

        return $this->blueDot->execute('callable.learning_games_presentation', [
            'learning_user_id' => $learningUserId,
        ])->getResult();
    }
}