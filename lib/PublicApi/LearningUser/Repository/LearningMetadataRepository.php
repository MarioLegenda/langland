<?php

namespace PublicApi\LearningUser\Repository;

use BlueDot\BlueDot;
use Library\Infrastructure\BlueDot\BaseBlueDotRepository;

class LearningMetadataRepository extends BaseBlueDotRepository
{
    /**
     * LearningMetadataRepository constructor.
     * @param BlueDot $blueDot
     * @param string $apiName
     */
    public function __construct(BlueDot $blueDot, string $apiName)
    {
        parent::__construct($blueDot, $apiName);
    }
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
        ]);
    }
}