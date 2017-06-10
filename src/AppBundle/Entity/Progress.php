<?php

namespace AppBundle\Entity;

/**
 * Progress
 */
class Progress
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var string $urls
     */
    private $urls;
    /**
     * @var string $text
     */
    private $text;
    /**
     * @var LearningUser $learningUser
     */
    private $learningUser;
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
     * @return string
     */
    public function getUrls(): string
    {
        return $this->urls;
    }
    /**
     * @param string $urls
     * @return Progress
     */
    public function setUrls($urls) : Progress
    {
        $this->urls = $urls;

        return $this;
    }
    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    /**
     * @param string $text
     * @return Progress
     */
    public function setText($text) : Progress
    {
        $this->text = $text;

        return $this;
    }
    /**
     * @return LearningUser
     */
    public function getLearningUser(): LearningUser
    {
        return $this->learningUser;
    }
    /**
     * @param LearningUser $learningUser
     * @return Progress
     */
    public function setLearningUser(LearningUser $learningUser) : Progress
    {
        $this->learningUser = $learningUser;

        return $this;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Progress
     */
    public function setCreatedAt($createdAt) : Progress
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
     * @return Progress
     */
    public function setUpdatedAt($updatedAt) : Progress
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

