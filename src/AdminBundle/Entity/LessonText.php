<?php

namespace AdminBundle\Entity;

/**
 * LessonText
 */
class LessonText
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
     * @var string $text
     */
    private $text;
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
     * @return LessonText
     */
    public function setName($name) : LessonText
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
     * Set text
     *
     * @param string $text
     *
     * @return LessonText
     */
    public function setText($text) : LessonText
    {
        $this->text = $text;

        return $this;
    }
    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
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
     * @return LessonText
     */
    public function setLesson(Lesson $lesson) : LessonText
    {
        $this->lesson = $lesson;

        return $this;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LessonText
     */
    public function setCreatedAt($createdAt) : LessonText
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
     * @return LessonText
     */
    public function setUpdatedAt($updatedAt) : LessonText
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

