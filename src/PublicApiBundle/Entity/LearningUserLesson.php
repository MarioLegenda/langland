<?php

namespace PublicApiBundle\Entity;

use AdminBundle\Entity\Lesson;

/**
 * LearningUserLesson
 */
class LearningUserLesson
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var bool $hasPassed
     */
    private $hasPassed;

    private $isEligable;
    /**
     * @var LearningUserCourse $learningUserCourse
     */
    private $learningUserCourse;
    /**
     * @var Lesson $lesson
     */
    private $lesson;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;

    public function __construct()
    {
        $this->hasPassed = false;
        $this->isEligable = false;
    }
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
     * Set hasPassed
     *
     * @param boolean $hasPassed
     *
     * @return LearningUserLesson
     */
    public function setHasPassed($hasPassed) : LearningUserLesson
    {
        $this->hasPassed = $hasPassed;

        return $this;
    }
    /**
     * Get hasPassed
     *
     * @return bool
     */
    public function getHasPassed()
    {
        return $this->hasPassed;
    }
    /**
     * @return mixed
     */
    public function getIsEligable()
    {
        return $this->isEligable;
    }
    /**
     * @param mixed $isEligable
     * @return LearningUserLesson
     */
    public function setIsEligable($isEligable) : LearningUserLesson
    {
        $this->isEligable = $isEligable;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getLearningUserCourse()
    {
        return $this->learningUserCourse;
    }
    /**
     * @param mixed $learningUserCourse
     * @return LearningUserLesson
     */
    public function setLearningUserCourse($learningUserCourse) : LearningUserLesson
    {
        $this->learningUserCourse = $learningUserCourse;

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
     * @return LearningUserLesson
     */
    public function setLesson(Lesson $lesson) : LearningUserLesson
    {
        $this->lesson = $lesson;

        return $this;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LearningUserLesson
     */
    public function setCreatedAt($createdAt) : LearningUserLesson
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
     * @return LearningUserLesson
     */
    public function setUpdatedAt($updatedAt) : LearningUserLesson
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

