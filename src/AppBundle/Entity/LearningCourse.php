<?php

namespace AppBundle\Entity;

/**
 * LearningCourse
 */
class LearningCourse
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var bool $isStarted
     */
    private $isStarted;
    /**
     * @var bool $isPassed
     */
    private $isPassed;
    /**
     * @var bool $isEligable
     */
    private $isEligable;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isStarted
     *
     * @param boolean $isStarted
     *
     * @return LearningCourse
     */
    public function setIsStarted($isStarted) : LearningCourse
    {
        $this->isStarted = $isStarted;

        return $this;
    }

    /**
     * Get isStarted
     *
     * @return bool
     */
    public function getIsStarted()
    {
        return $this->isStarted;
    }
    /**
     * Set isPassed
     *
     * @param boolean $isPassed
     *
     * @return LearningCourse
     */
    public function setIsPassed($isPassed) : LearningCourse
    {
        $this->isPassed = $isPassed;

        return $this;
    }
    /**
     * Get isPassed
     *
     * @return bool
     */
    public function getIsPassed()
    {
        return $this->isPassed;
    }

    /**
     * Set isEligable
     *
     * @param boolean $isEligable
     *
     * @return LearningCourse
     */
    public function setIsEligable($isEligable) : LearningCourse
    {
        $this->isEligable = $isEligable;

        return $this;
    }
    /**
     * Get isEligable
     *
     * @return bool
     */
    public function getIsEligable()
    {
        return $this->isEligable;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LearningCourse
     */
    public function setCreatedAt(\DateTime $createdAt) : LearningCourse
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return LearningCourse
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}

