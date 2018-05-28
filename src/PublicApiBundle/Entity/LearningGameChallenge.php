<?php

namespace PublicApiBundle\Entity;

class LearningGameChallenge
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var DataCollector $dataCollector
     */
    private $dataCollector;
    /**
     * @var LearningUser $learningUser
     */
    private $learningUser;
    /**
     * @var LearningGame $learningGame
     */
    private $learningGame;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime|null $updatedAt
     */
    private $updatedAt;
    /**
     * LearningGameChallenge constructor.
     * @param DataCollector $dataCollector
     * @param LearningUser $learningUser
     * @param LearningGame $learningGame
     */
    public function __construct(
        DataCollector $dataCollector,
        LearningUser $learningUser,
        LearningGame $learningGame
    ) {
        $this->dataCollector = $dataCollector;
        $this->learningUser = $learningUser;
        $this->learningGame = $learningGame;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return DataCollector
     */
    public function getDataCollector(): DataCollector
    {
        return $this->dataCollector;
    }
    /**
     * @param DataCollector $dataCollector
     */
    public function setDataCollector(DataCollector $dataCollector): void
    {
        $this->dataCollector = $dataCollector;
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
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
    /**
     * @param \DateTime|null $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void
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