<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * WorkingData
 */
class LessonWorkingData
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var \stdClass
     */
    private $sentences;
    /**
     * @var \stdClass
     */
    private $wordPools;
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
        $this->sentences = new ArrayCollection();
        $this->wordPools = new ArrayCollection();
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
     * Set sentences
     *
     * @param Sentence[] $sentences
     *
     * @return LessonWorkingData
     */
    public function setSentences($sentences) : LessonWorkingData
    {
        $this->sentences = $sentences;

        return $this;
    }
    /**
     * Get sentences
     *
     * @return \stdClass
     */
    public function getSentences()
    {
        return $this->sentences;
    }
    /**
     * Set wordPools
     *
     * @param SentenceWordPool[] $wordPools
     *
     * @return LessonWorkingData
     */
    public function setWordPools($wordPools) : LessonWorkingData
    {
        $this->wordPools = $wordPools;

        return $this;
    }
    /**
     * Get wordPools
     *
     * @return \stdClass
     */
    public function getWordPools()
    {
        return $this->wordPools;
    }
    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime $createdAt
     * @return LessonWorkingData
     */
    public function setCreatedAt(\DateTime $createdAt) : LessonWorkingData
    {
        $this->createdAt = $createdAt;

        return $this;
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
     * @return LessonWorkingData
     */
    public function setUpdatedAt(\DateTime $updatedAt) : LessonWorkingData
    {
        $this->updatedAt = $updatedAt;

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

