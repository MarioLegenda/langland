<?php

namespace AppBundle\Entity;

use AdminBundle\Entity\Course;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * LearningUserCourse
 */
class LearningUserCourse
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @var LearningUser
     */
    private $learningUser;
    /**
     * @var ArrayCollection $courses
     */
    private $course;
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
     * Set learningUser
     *
     * @param LearningUser $learningUser
     *
     * @return LearningUserCourse
     */
    public function setLearningUser($learningUser) : LearningUserCourse
    {
        $this->learningUser = $learningUser;

        return $this;
    }
    /**
     * Get learningUser
     *
     * @return LearningUser
     */
    public function getLearningUser()
    {
        return $this->learningUser;
    }
    /**
     * Set courses
     *
     * @param \stdClass $courses
     *
     * @return LearningUserCourse
     */
    public function setCourse($courses) : LearningUserCourse
    {
        $this->course = $courses;

        return $this;
    }
    /**
     * Get courses
     *
     * @return ArrayCollection
     */
    public function getCourse()
    {
        return $this->course;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LearningUserCourse
     */
    public function setCreatedAt($createdAt) : LearningUserCourse
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
     * @return LearningUserCourse
     */
    public function setUpdatedAt($updatedAt) : LearningUserCourse
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

