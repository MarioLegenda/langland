<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * SentenceWordPool
 */
class SentenceWordPool
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var \DateTime
     */
    private $createdAt;
    /**
     * @var \DateTime
     */
    private $updatedAt;
    /**
     * @var Course $course
     */
    private $course;
    /**
     * @var ArrayCollection $words
     */
    private $words;

    public function __construct()
    {
        $this->words = new ArrayCollection();
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
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param mixed $name
     * @return SentenceWordPool
     */
    public function setName($name) : SentenceWordPool
    {
        $this->name = $name;

        return $this;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return SentenceWordPool
     */
    public function setCreatedAt(\DateTime $createdAt) : SentenceWordPool
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
     * @return SentenceWordPool
     */
    public function setUpdatedAt(\DateTime $updatedAt) : SentenceWordPool
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
    /**
     * @return Course
     */
    public function getCourse()
    {
        return $this->course;
    }
    /**
     * @param Course $course
     * @return SentenceWordPool
     */
    public function setCourse(Course $course) : SentenceWordPool
    {
        $this->course = $course;

        return $this;
    }
    /**
     * @param Word $word
     * @return bool
     */
    public function hasWord(Word $word) : bool
    {
        return $this->words->contains($word);
    }
    /**
     * @param Word $word
     * @return SentenceWordPool
     */
    public function addWord(Word $word) : SentenceWordPool
    {
        if (!$this->hasWord($word)) {
            $this->words->add($word);
        }

        return $this;
    }
    /**
     * @param mixed $words
     * @return SentenceWordPool
     */
    public function setWords($words) : SentenceWordPool
    {
        $this->words = $words;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getWords()
    {
        return $this->words;
    }

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}

