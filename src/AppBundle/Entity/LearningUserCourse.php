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
     * @var $learningUserLessons
     */
    private $learningUserLessons;
    /**
     * @var Course $courses
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
        $this->learningUserLessons = new ArrayCollection();
        $this->hasPassed = false;
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
    /**
     * @param LearningUserLesson $lesson
     * @return bool
     */
    public function hasLearningUserLesson(LearningUserLesson $lesson) : bool
    {
        return $this->learningUserLessons->contains($lesson);
    }
    /**
     * @param LearningUserLesson $lesson
     * @return LearningUserCourse
     */
    public function addLearningUserLesson(LearningUserLesson $lesson) : LearningUserCourse
    {
        if (!$this->hasLearningUserLesson($lesson)) {
            $lesson->setLearningUserCourse($this);
            $this->learningUserLessons->add($lesson);
        }

        return $this;
    }
    /**
     * @param LearningUserLesson $lesson
     * @return LearningUserCourse
     */
    public function removeLearningUserLesson(LearningUserLesson $lesson) : LearningUserCourse
    {
        if ($this->hasLearningUserLesson($lesson)) {
            $this->learningUserLessons->removeElement($lesson);
        }

        return $this;
    }
    /**
     * @param $learningUserLessons
     * @return LearningUserCourse
     */
    public function setLearningUserLessons($learningUserLessons) : LearningUserCourse
    {
        $this->learningUserLessons = $learningUserLessons;

        return $this;
    }
    /**
     * @return ArrayCollection
     */
    public function getLearningUserLessons()
    {
        return $this->learningUserLessons;
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

