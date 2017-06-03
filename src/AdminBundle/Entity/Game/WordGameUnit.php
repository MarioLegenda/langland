<?php

namespace AdminBundle\Entity\Game;

use AdminBundle\Entity\Word;

/**
 * WordGameUnit
 */
class WordGameUnit
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
     * @var Word $word
     */
    private $word;
    /**
     * @var WordGame $game
     */
    private $game;
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
    public function getWord()
    {
        return $this->word;
    }
    /**
     * @param mixed $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }
    /**
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
    }
    /**
     * @param mixed $game
     * @return WordGameUnit
     */
    public function setGame($game) : WordGameUnit
    {
        $this->game = $game;

        return $this;
    }
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return WordGameUnit
     */
    public function setCreatedAt($createdAt) : WordGameUnit
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
     * @return WordGameUnit
     */
    public function setUpdatedAt($updatedAt) : WordGameUnit
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

