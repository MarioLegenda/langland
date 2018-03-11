<?php

namespace LearningSystemBundle\Entity;

class DataCollector
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var bool $hasCompleted
     */
    private $hasCompleted = false;
    /**
     * @var int $timeSpent
     */
    private $timeSpent = null;
    /**
     * @var int $accessedCount
     */
    private $accessedCount;
    /**
     * @var int $completedCount
     */
    private $completedCount;
    /**
     * @var int $unCompletedCount
     */
    private $unCompletedCount;
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @param int $id
     * @return DataCollector
     */
    public function setId(int $id): DataCollector
    {
        $this->id = $id;

        return $this;
    }
    /**
     * @return int
     */
    public function getAccessedCount(): int
    {
        return $this->accessedCount;
    }
    /**
     * @param int $accessedCount
     * @return DataCollector
     */
    public function setAccessedCount(int $accessedCount): DataCollector
    {
        $this->accessedCount = $accessedCount;

        return $this;
    }
    /**
     * @return int
     */
    public function getCompletedCount(): int
    {
        return $this->completedCount;
    }
    /**
     * @param int $completedCount
     * @return DataCollector
     */
    public function setCompletedCount(int $completedCount): DataCollector
    {
        $this->completedCount = $completedCount;

        return $this;
    }
    /**
     * @return int
     */
    public function getUnCompletedCount(): int
    {
        return $this->unCompletedCount;
    }
    /**
     * @param int $unCompletedCount
     * @return DataCollector
     */
    public function setUnCompletedCount(int $unCompletedCount): DataCollector
    {
        $this->unCompletedCount = $unCompletedCount;

        return $this;
    }
    /**
     * @return bool
     */
    public function getHasCompleted(): bool
    {
        return $this->hasCompleted;
    }

    /**
     * @param bool $hasCompleted
     * @return DataCollector
     */
    public function setHasCompleted(bool $hasCompleted): DataCollector
    {
        $this->hasCompleted = $hasCompleted;

        return $this;
    }
    /**
     * @return int
     */
    public function getTimeSpent(): int
    {
        return $this->timeSpent;
    }

    /**
     * @param int $timeSpent
     * @return DataCollector
     */
    public function setTimeSpent(int $timeSpent): DataCollector
    {
        $this->timeSpent = $timeSpent;

        return $this;
    }
}