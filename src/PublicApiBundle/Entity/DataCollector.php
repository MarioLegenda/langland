<?php

namespace PublicApiBundle\Entity;

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
    private $accessedCount = 0;
    /**
     * @var int $completedCount
     */
    private $completedCount = 0;
    /**
     * @var int $unCompletedCount
     */
    private $unCompletedCount = 0;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * DataCollector constructor.
     * @param bool $hasCompleted
     * @param int $timeSpent
     * @param int $accessedCount
     * @param int $completedCount
     * @param int $unCompletedCount
     */
    public function __construct(
        bool $hasCompleted = false,
        int $timeSpent = 0,
        int $accessedCount = 0,
        int $completedCount = 0,
        int $unCompletedCount = 0
    ) {
        $this->hasCompleted = $hasCompleted;
        $this->timeSpent = $timeSpent;
        $this->accessedCount = $accessedCount;
        $this->completedCount = $completedCount;
        $this->unCompletedCount = $unCompletedCount;
    }
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
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function updateTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}