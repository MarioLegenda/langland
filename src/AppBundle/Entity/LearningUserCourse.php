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
     * @var bool $hasPassed
     */
    private $hasPassed;
    /**
     * @var CourseHolder $courseHolder
     */
    private $courseHolder;
    /**
     * @var ArrayCollection $courses
     */
    private $course;
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
        $this->courses = new ArrayCollection();
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
     * @return bool
     */
    public function getHasPassed(): bool
    {
        return $this->hasPassed;
    }
    /**
     * @param bool $hasPassed
     * @return LearningUserCourse
     */
    public function setHasPassed(bool $hasPassed) : LearningUserCourse
    {
        $this->hasPassed = $hasPassed;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getCourseHolder()
    {
        return $this->courseHolder;
    }
    /**
     * @param mixed $courseHolder
     */
    public function setCourseHolder($courseHolder)
    {
        $this->courseHolder = $courseHolder;
    }
    /**
     * @param $courses
     * @return LearningUserCourse
     */
    public function setCourses($courses) : LearningUserCourse
    {
        $this->courses = $courses;
    }
    /**
     * @return ArrayCollection
     */
    public function getCourses()
    {
        return $this->courses;
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
    /**
     * @return ArrayCollection
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param Course $course
     */
    public function setCourse(Course $course)
    {
        $this->course = $course;
    }
}

