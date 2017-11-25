<?php

namespace AdminBundle\Entity;

class BasicWordGame
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
     * @var Lesson $lesson
     */
    private $lesson;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime
     */
    private $updatedAt;
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param string $name
     *
     * @return BasicWordGame
     */
    public function setName($name): BasicWordGame
    {
        $this->name = $name;

        return $this;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @return BasicWordGame
     */
    public function setLesson(Lesson $lesson) : BasicWordGame
    {
        $this->lesson = $lesson;

        return $this;
    }
    /**
     * @param \DateTime $createdAt
     *
     * @return BasicWordGame
     */
    public function setCreatedAt($createdAt) : BasicWordGame
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return BasicWordGame
     */
    public function setUpdatedAt($updatedAt) : BasicWordGame
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * @void
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}

