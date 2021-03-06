<?php

namespace PublicApiBundle\Entity;

use LearningSystemBundle\Entity\DataCollector;

class LearningMetadata
{
    /**
     * @var DataCollector $dataCollector
     */
    private $learningLessonDataCollector;
    /**
     * @var bool $hasCompleted
     */
    private $hasCompleted = false;
    /**
     * @var int $learningUserId
     */
    private $learningUser;
}