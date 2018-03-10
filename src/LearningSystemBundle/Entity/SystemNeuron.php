<?php

namespace LearningSystemBundle\Entity;

class SystemNeuron
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var bool $hasCompleted
     */
    private $hasCompleted = false;
    /**
     * @var int $timeSpent
     */
    private $timeSpent = 0;
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
     * @return SystemNeuron
     */
    public function setId(int $id): SystemNeuron
    {
        $this->id = $id;
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return SystemNeuron
     */
    public function setName(string $name): SystemNeuron
    {
        $this->name = $name;
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
     * @return SystemNeuron
     */
    public function setAccessedCount(int $accessedCount): SystemNeuron
    {
        $this->accessedCount = $accessedCount;
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
     * @return SystemNeuron
     */
    public function setCompletedCount(int $completedCount): SystemNeuron
    {
        $this->completedCount = $completedCount;
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
     * @return SystemNeuron
     */
    public function setUnCompletedCount(int $unCompletedCount): SystemNeuron
    {
        $this->unCompletedCount = $unCompletedCount;
    }
}