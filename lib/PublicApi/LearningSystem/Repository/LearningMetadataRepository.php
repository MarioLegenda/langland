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
    ): array {
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
        return $this->blueDot->execute('service.create_learning_metadata', [
            'language_id' => $languageId,
            'learning_user_id' => $learningUserId,
        ])->getResult()['data'];
    }
    /**
     * @param int $learningUserId
     * @param int $languageId
     * @return array
     */
    public function getLearningLessonPresentation(
        int $learningUserId,
        int $languageId
    ): array {
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