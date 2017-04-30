<?php

namespace AdminBundle\Entity;

/**
 * Lesson
 */
class Lesson
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
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @var Course $course
     */
    private $course;

    private $workingData;
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
     * Set name
     *
     * @param string $name
     *
     * @return Lesson
     */
    public function setName($name) : Lesson
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @return mixed
     */
    public function getWorkingData()
    {
        return $this->workingData;
    }
    /**
     * @param mixed $workingData
     */
    public function setWorkingData($workingData)
    {
        $this->workingData = $workingData;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Lesson
     */
    public function setCreatedAt(\DateTime $createdAt) : Lesson
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
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTime $updatedAt
     * @return Lesson
     */
    public function setUpdatedAt(\DateTime $updatedAt) : Lesson
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * Set course
     *
     * @param string $course
     *
     * @return Lesson
     */
    public function setCourse($course) : Lesson
    {
        $this->course = $course;

        return $this;
    }
    /**
     * Get course
     *
     * @return string
     */
    public function getCourse()
    {
        return $this->course;
    }

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}

