<?php

namespace PublicApiBundle\Entity;

use PublicApiBundle\Entity\Contract\CollectibleDataContainerInterface;

class LearningGameChallenge implements CollectibleDataContainerInterface
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var LearningMetadata $learningMetadata
     */
    private $learningMetadata;
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
     * @param LearningGame $learningGame
     * @param LearningMetadata $learningMetadata
     */
    public function __construct(
        LearningMetadata $learningMetadata,
        LearningGame $learningGame
    ) {
        $this->learningGame = $learningGame;
        $this->learningMetadata = $learningMetadata;
    }
    /**
     * @return LearningMetadata
     */
    public function getLearningMetadata(): LearningMetadata
    {
        return $this->learningMetadata;
    }
    /**
     * @param LearningMetadata $learningMetadata
     */
    public function setLearningMetadata(LearningMetadata $learningMetadata): void
    {
        $this->learningMetadata = $learningMetadata;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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