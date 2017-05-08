<?php

namespace AppBundle\Entity;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

class LearningUser
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
     * @var UserInterface $user
     */
    private $user;
    /**
     * @var ArrayCollection $courses
     */
    private $courses;
    /**
     * @var Course $currentCourse
     */
    private $currentCourse;

    public function __construct()
    {
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LearningUser
     */
    public function setCreatedAt(\DateTime $createdAt) : LearningUser
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
     * @return LearningUser
     */
    public function setUpdatedAt(\DateTime $updatedAt) : LearningUser
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @param UserInterface $user
     * @return LearningUser
     */
    public function setUser($user) : LearningUser
    {
        $this->user = $user;

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
     * @param Course $course
     * @return bool
     */
    public function hasCourse(Course $course) : bool
    {
        return $this->courses->contains($course);
    }
    /**
     * @param Course $course
     * @return LearningUser
     */
    public function addLanguage(Course $course) : LearningUser
    {
        if (!$this->hasCourse($course)) {
            $this->courses->add($course);
        }

        return $this;
    }
    /**
     * @return mixed
     */
    public function getLanguages()
    {
        return $this->courses;
    }
    /**
     * @param mixed $courses
     */
    public function setCourses($courses)
    {
        $this->courses = $courses;
    }
    /**
     * @return mixed
     */
    public function getCurrentCourse()
    {
        return $this->currentCourse;
    }
    /**
     * @param mixed $currentCourse
     * @return LearningUser
     */
    public function setCurrentCourse($currentCourse) : LearningUser
    {
        $this->currentCourse = $currentCourse;

        return $this;
    }
}

