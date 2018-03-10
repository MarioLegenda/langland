<?php

namespace PublicApiBundle\Entity;

use LearningSystemBundle\Entity\SystemHead;

class LearningMetadata
{
    /**
     * @var SystemHead $learningLessonSystemHead
     */
    private $learningLessonSystemHead;
    /**
     * @var bool $hasCompleted
     */
    private $hasCompleted = false;
    /**
     * @var bool $isCurrent
     */
    private $isCurrent = false;
    /**
     * @var int $learningUserId
     */
    private $learningUser;
}