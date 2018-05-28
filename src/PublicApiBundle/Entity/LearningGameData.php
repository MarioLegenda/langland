<?php

namespace PublicApiBundle\Entity;

class LearningGameData
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var LearningGameChallenge $learningGameChallenge
     */
    private $learningGameChallenge;
    /**
     * @var LearningGame $learningGame
     */
    private $learningGame;
    /**
     * @var int $dataId
     */
    private $dataId;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * LearningGameData constructor.
     * @param LearningGameChallenge $learningGameChallenge
     * @param LearningGame $learningGame
     * @param int $dataId
     */
    public function __construct(
        LearningGameChallenge $learningGameChallenge,
        LearningGame $learningGame,
        int $dataId
    ) {
        $this->learningGameChallenge = $learningGameChallenge;
        $this->learningGame = $learningGame;
        $this->dataId = $dataId;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @return LearningGameChallenge
     */
    public function getLearningGameChallenge(): LearningGameChallenge
    {
        return $this->learningGameChallenge;
    }
    /**
     * @param LearningGameChallenge $learningGameChallenge
     */
    public function setLearningGameChallenge(LearningGameChallenge $learningGameChallenge): void
    {
        $this->learningGameChallenge = $learningGameChallenge;
    }
    /**
     * @return LearningGame
     */
    public function getLearningGame(): LearningGame
    {
        return $this->learningGame;
    }
    /**
     * @param LearningGame $learningGame
     */
    public function setLearningGame(LearningGame $learningGame): void
    {
        $this->learningGame = $learningGame;
    }
    /**
     * @return int
     */
    public function getDataId(): int
    {
        return $this->dataId;
    }
    /**
     * @param int $dataId
     */
    public function setDataId(int $dataId): void
    {
        $this->dataId = $dataId;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function updateTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}