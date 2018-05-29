<?php

namespace PublicApiBundle\Entity;

use PublicApiBundle\Entity\Contract\CollectibleDataContainerInterface;

class LearningGame implements CollectibleDataContainerInterface
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
     * @var string $type
     */
    private $type;
    /**
     * @var LearningUser $learningUser
     */
    private $learningUser;
    /**
     * @var LearningMetadata $learningMetadata
     */
    private $learningMetadata;
    /**
     * @var LearningLesson $learningLesson
     */
    private $learningLesson;
    /**
     * @var bool $hasCompleted
     */
    private $hasCompleted = false;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime|null $updatedAt
     */
    private $updatedAt;
    /**
     * LearningGame constructor.
     * @param string $name
     * @param string $type
     * @param LearningUser $learningUser
     * @param LearningMetadata $learningMetadata
     * @param LearningLesson $learningLesson
     * @param bool $hasCompleted
     */
    public function __construct(
        string $name,
        string $type,
        LearningUser $learningUser,
        LearningMetadata $learningMetadata,
        LearningLesson $learningLesson,
        bool $hasCompleted
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->learningUser = $learningUser;
        $this->learningMetadata = $learningMetadata;
        $this->learningLesson = $learningLesson;
        $this->hasCompleted = $hasCompleted;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
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
     */
    public function setLearningUser(LearningUser $learningUser): void
    {
        $this->learningUser = $learningUser;
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
     * @return LearningLesson
     */
    public function getLearningLesson(): LearningLesson
    {
        return $this->learningLesson;
    }
    /**
     * @param LearningLesson $learningLesson
     */
    public function setLearningLesson(LearningLesson $learningLesson): void
    {
        $this->learningLesson = $learningLesson;
    }
    /**
     * @return bool
     */
    public function isHasCompleted(): bool
    {
        return $this->hasCompleted;
    }
    /**
     * @param bool $hasCompleted
     */
    public function setHasCompleted(bool $hasCompleted): void
    {
        $this->hasCompleted = $hasCompleted;
    }
    /**
     * @return \DateTime|null
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