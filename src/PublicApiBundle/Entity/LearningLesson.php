<?php

namespace PublicApiBundle\Entity;

use AdminBundle\Entity\Lesson;
use LearningSystem\Library\Repository\Contract\SystemHeadInterface;
use LearningSystemBundle\Entity\DataCollector;

class LearningLesson
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var DataCollector $systemNeuron
     */
    private $dataCollector;
    /**
     * @var Lesson $lesson
     */
    private $lesson;
    /**
     * @var bool $hasPassed
     */
    private $hasCompleted = false;
    /**
     * @param int $id
     * @return LearningLesson
     */
    public function setId(int $id): LearningLesson
    {
        $this->id = $id;

        return $this;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @return bool
     */
    public function hasCompleted(): bool
    {
        return $this->hasCompleted;
    }
    /**
     * @param bool $hasCompleted
     * @return LearningLesson
     */
    public function setAsCompleted(bool $hasCompleted): LearningLesson
    {
        $this->hasCompleted = $hasCompleted;

        return $this;
    }
    /**
     * @return Lesson
     */
    public function getLesson(): Lesson
    {
        return $this->lesson;
    }
    /**
     * @param Lesson $lesson
     * @return LearningLesson
     */
    public function setLesson(Lesson $lesson): LearningLesson
    {
        $this->lesson = $lesson;

        return $this;
    }
}