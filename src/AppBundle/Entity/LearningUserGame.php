<?php

namespace AppBundle\Entity;

use AdminBundle\Entity\Game\QuestionGame;
use AdminBundle\Entity\Game\WordGame;

/**
 * LearningUserGame
 */
class LearningUserGame
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var bool $hasPassed
     */
    private $hasPassed;
    /**
     * @var LearningUserLesson $learningUserLesson
     */
    private $learningUserLesson;
    /**
     * @var QuestionGame $questionGame
     */
    private $questionGame;
    /**
     * @var WordGame $wordGame
     */
    private $wordGame;
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
        $this->hasPassed = false;
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
     * Set hasPassed
     *
     * @param boolean $hasPassed
     *
     * @return LearningUserGame
     */
    public function setHasPassed($hasPassed)
    {
        $this->hasPassed = $hasPassed;

        return $this;
    }
    /**
     * Get hasPassed
     *
     * @return bool
     */
    public function getHasPassed()
    {
        return $this->hasPassed;
    }
    /**
     * @return LearningUserLesson
     */
    public function getLearningUserLesson(): LearningUserLesson
    {
        return $this->learningUserLesson;
    }
    /**
     * @param LearningUserLesson $learningUserLesson
     * @return LearningUserGame
     */
    public function setLearningUserLesson(LearningUserLesson $learningUserLesson) : LearningUserGame
    {
        $this->learningUserLesson = $learningUserLesson;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getQuestionGame()
    {
        return $this->questionGame;
    }
    /**
     * @param mixed $questionGame
     * @return LearningUserGame
     */
    public function setQuestionGame($questionGame) : LearningUserGame
    {
        $this->questionGame = $questionGame;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getWordGame()
    {
        return $this->wordGame;
    }
    /**
     * @param mixed $wordGame
     * @return LearningUserGame
     */
    public function setWordGame($wordGame) : LearningUserGame
    {
        $this->wordGame = $wordGame;

        return $this;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LearningUserGame
     */
    public function setCreatedAt($createdAt)
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
     * @return LearningUserGame
     */
    public function setUpdatedAt($updatedAt)
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

