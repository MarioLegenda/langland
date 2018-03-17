<?php

namespace PublicApi\LearningUser\Repository;

use BlueDot\Entity\PromiseInterface;
use Library\Infrastructure\BlueDot\BaseBlueDotRepository;

class LearningMetadataRepository extends BaseBlueDotRepository
{
    /**
     * @param array $parameters
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function createLearningMetadata(array $parameters)
    {
        $this->blueDot->useRepository($this->apiName);

        $this->blueDot->execute('scenario.create_learning_metadata', [
            'find_lesson' => [
                'language_id' => $parameters['language_id'],
                'course_type' => $parameters['course_type'],
                'learning_order' => $parameters['learning_order'],
            ],
            'create_learning_lesson' => [
                'lesson_id' => $parameters['lesson_id'],
            ],
            'create_learning_metadata' => [
                'learning_user_id' => $parameters['learning_user_id'],
            ],
        ])->success(function(PromiseInterface $promise) {
            $learningMetadataId = $promise->getResult()->get('create_learning_metadata')->get('last_insert_id');
            $learningLessonId = $promise->getResult()->get('create_learning_lesson')->get('last_insert_id');

            $this->blueDot->execute('simple.update.update_learning_lesson', [
                'learning_metadata_id' => $learningMetadataId,
                'learning_lesson_id' => $learningLessonId,
            ]);
        });
    }
}