<?php

namespace PublicApiBundle\Entity;

use AdminBundle\Entity\Language;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CourseHolder
 */
class CourseHolder
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var $learningUser
     */
    private $learningUser;
    /**
     * @var ArrayCollection $learningUserCourses
     */
    private $learningUserCourses;
    /**
     * @var Language $language
     */
    private $language;
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
        $this->learningUserCourses = new ArrayCollection();
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
     * @return \DateTime
     */
    public function getLearningUser()
    {
        return $this->learningUser;
    }
    /**
     * @param \DateTime $learningUser
     */
    public function setLearningUser($learningUser)
    {
        $this->learningUser = $learningUser;
    }
    /**
     * @param LearningUserCourse $learningUserCourse
     * @return bool
     */
    public function hasLearningUserCourse(LearningUserCourse $learningUserCourse) : bool
    {
        return $this->learningUserCourses->contains($learningUserCourse);
    }
    /**
     * @param LearningUserCourse $learningUserCourse
     * @return CourseHolder
     */
    public function addLearningUserCourse(LearningUserCourse $learningUserCourse) : CourseHolder
    {
        if (!$this->hasLearningUserCourse($learningUserCourse)) {
            $learningUserCourse->setCourseHolder($this);
            $this->learningUserCourses->add($learningUserCourse);
        }

        return $this;
    }
    /**
     * @param LearningUserCourse $learningUserCourse
     * @return CourseHolder
     */
    public function removeLearningUserCourse(LearningUserCourse $learningUserCourse) : CourseHolder
    {
        if ($this->hasLearningUserCourse($learningUserCourse)) {
            $this->learningUserCourses->removeElement($learningUserCourse);
        }

        return $this;
    }
    /**
     * @param $learningUserCourses
     */
    public function setLearningUserCourses($learningUserCourses)
    {
        $this->learningUserCourses = $learningUserCourses;
    }
    /**
     * @return ArrayCollection
     */
    public function getLearningUserCourses()
    {
        return $this->learningUserCourses;
    }
    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }
    /**
     * @param Language $language
     */
    public function setLanguage(Language $language)
    {
        $this->language = $language;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CourseHolder
     */
    public function setCreatedAt($createdAt) : CourseHolder
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
     * @return CourseHolder
     */
    public function setUpdatedAt($updatedAt) : CourseHolder
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

