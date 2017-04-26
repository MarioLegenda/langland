<?php

namespace AdminBundle\Entity;

/**
 * SentenceTranslation
 */
class SentenceTranslation
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
     * @var int $markedCorrect
     */
    private $markedCorrect;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * @var Sentence
     */
    private $sentence;
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
     * @return SentenceTranslation
     */
    public function setName($name) : SentenceTranslation
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
     * Set markedCorrect
     *
     * @param integer $markedCorrect
     *
     * @return SentenceTranslation
     */
    public function setMarkedCorrect($markedCorrect) : SentenceTranslation
    {
        $this->markedCorrect = $markedCorrect;

        return $this;
    }
    /**
     * Get markedCorrect
     *
     * @return int
     */
    public function getMarkedCorrect()
    {
        return $this->markedCorrect;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return SentenceTranslation
     */
    public function setCreatedAt($createdAt) : SentenceTranslation
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
     * @return SentenceTranslation
     */
    public function setUpdatedAt(\DateTime $updatedAt) : SentenceTranslation
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getSentence()
    {
        return $this->sentence;
    }
    /**
     * @param mixed $sentence
     */
    public function setSentence($sentence)
    {
        $this->sentence = $sentence;
    }

    public function updateTimestamps()
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}

