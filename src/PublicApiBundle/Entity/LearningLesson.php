<?php

namespace PublicApiBundle\Entity;

use PublicApi\Infrastructure\Model\Lesson;
use PublicApiBundle\Entity\Contract\CollectibleDataContainerInterface;

class LearningLesson implements CollectibleDataContainerInterface
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var LearningUser $learningUser
     */
    private $learningUser;
    /**
     * @var int|Lesson $lesson
     */
    private $lesson;
    /**
     * @var Lesson $lessonObject
     */
    private $lessonObject;
    /**
     * @var LearningMetadata $learningMetadata
     */
    private $learningMetadata;
    /**
     * @var bool $hasPassed
     */
    private $hasCompleted = false;
    /**
     * @var bool $isAvailable
     */
    private $isAvailable = false;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * LearningLesson constructor.
     * @param LearningUser $learningUser
     * @param Lesson $lesson
     * @param LearningMetadata $learningMetadata
     * @param bool $hasCompleted
     * @param bool $isAvailable
     */
    public function __construct(
        LearningUser $learningUser,
        Lesson $lesson,
        LearningMetadata $learningMetadata,
        bool $hasCompleted,
        bool $isAvailable
    ) {
        $this->learningUser = $learningUser;
        $this->lesson = $lesson->getId();
        $this->lessonObject = $lesson;
        $this->learningMetadata = $learningMetadata;
        $this->hasCompleted = $hasCompleted;
        $this->isAvailable = $isAvailable;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return bool
     */
    public function hasCompleted(): bool
    {
        return $this->hasCompleted;
    }
    /**
     * @param bool $hasCompleted
     * @return LearningLesson
     */
    public function setAsCompleted(bool $hasCompleted): LearningLesson
    {
        $this->hasCompleted = $hasCompleted;

        return $this;
    }
    /**
     * @return int
     */
    public function getLesson(): int
    {
        return $this->lesson;
    }
    /**
     * @param Lesson $lesson
     * @return LearningLesson
     */
    public function setLesson(Lesson $lesson): LearningLesson
    {
        $this->lesson = $lesson;

        return $this;
    }
    /**
     * @return Lesson
     */
    public function getLessonObject(): Lesson
    {
        return $this->lessonObject;
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
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function updateTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime());

        if (!$this->getCreatedAt() instanceof \DateTime) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}