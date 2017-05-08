<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Course
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
     * @var Language $language
     */
    private $language;

    private $showOnPage;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @var ArrayCollection $lessons
     */
    private $lessons;

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Course
     */
    public function setName($name) : Course
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
    public function getShowOnPage()
    {
        return $this->showOnPage;
    }
    /**
     * @param mixed $showOnPage
     * @return Course
     */
    public function setShowOnPage($showOnPage) : Course
    {
        $this->showOnPage = $showOnPage;

        return $this;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Course
     */
    public function setCreatedAt(\DateTime $createdAt) : Course
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * Get createdAt
     *
     * @return string
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
     * @return Course
     */
    public function setUpdatedAt(\DateTime $updatedAt) : Course
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }
    /**
     * @param mixed $language
     * @return Course
     */
    public function setLanguage($language) : Course
    {
        $this->language = $language;

        return $this;
    }
    /**
     * @return ArrayCollection
     */
    public function getLessons()
    {
        return $this->lessons;
    }
    /**
     * @param Lesson $lesson
     * @return bool
     */
    public function hasLesson(Lesson $lesson) : bool
    {
        return $this->lessons->contains($lesson);
    }
    /**
     * @param Lesson $lesson
     * @return Course
     */
    public function addLesson(Lesson $lesson) : Course
    {
        if (!$this->hasLesson($lesson)) {
            $lesson->setCourse($this);
            $this->lessons->add($lesson);
        }

        return $this;
    }
    /**
     * @param ArrayCollection $lessons
     * @return Course
     */
    public function setLessons($lessons) : Course
    {
        foreach ($lessons as $lesson) {
            $this->addLesson($lesson);
        }

        return $this;
    }

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}

