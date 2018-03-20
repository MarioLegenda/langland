<?php

namespace PublicApi\LearningSystem\Repository;

use BlueDot\Entity\PromiseInterface;
use Library\Infrastructure\BlueDot\BaseBlueDotRepository;
use PublicApi\Infrastructure\Type\CourseType;
use PublicApi\Infrastructure\Type\TypeInterface;

class LearningMetadataRepository extends BaseBlueDotRepository
{
    /**
     * @param TypeInterface $courseType
     * @param int $courseLearningOrder
     * @param int $lessonLearningOrder
     * @param int $languageId
     * @param int $learningUserId
     * @return array
     */
    public function createLearningMetadata(
        TypeInterface $courseType,
        int $courseLearningOrder,
        int $lessonLearningOrder,
        int $languageId,
        int $learningUserId
    ): array
    {
        $this->blueDot->useRepository($this->apiName);

        return $this->doCreateLearningMetadata(
            $courseType,
            $courseLearningOrder,
            $lessonLearningOrder,
            $languageId,
            $learningUserId
        );
    }
    /**
     * @param TypeInterface $courseType
     * @param int $courseLearningOrder
     * @param int $lessonLearningOrder
     * @param int $languageId
     * @param int $learningUserId
     * @return array
     */
    private function doCreateLearningMetadata(
        TypeInterface $courseType,
        int $courseLearningOrder,
        int $lessonLearningOrder,
        int $languageId,
        int $learningUserId
    ) {
        $promise = $this->blueDot->execute('scenario.create_learning_metadata', [
            'find_course' => [
                'language_id' => $languageId,
                'course_order' => $courseLearningOrder,
                'course_type' => (string) $courseType,
            ],
            'find_lesson' => [
                'learning_order' => $lessonLearningOrder,
            ],
            'create_learning_metadata' => [
                'learning_user_id' => $learningUserId,
            ],
        ]);

        if ($promise->isSuccess()) {
            $learningMetadataId = $promise->getResult()->get('create_learning_metadata')->get('last_insert_id');
            $learningLessonId = $promise->getResult()->get('create_learning_lesson')->get('last_insert_id');

            $this->blueDot->execute('simple.update.update_learning_lesson', [
                'learning_metadata_id' => $learningMetadataId,
                'learning_lesson_id' => $learningLessonId,
            ]);
        }

        $result = $promise->getResult();

        return [
            'learningMetadataId' => (int) $result->get('create_learning_metadata')->get('last_insert_id'),
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

        return $this->blueDot->execute('callable.learning_lesson_presentation', [
            'learning_user_id' => $learningUserId,
            'language_id' => $languageId,
        ])->getResult();
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