<?php

namespace PublicApiBundle\Entity;

use PublicApi\Infrastructure\Model\Lesson;

class LearningLesson
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var DataCollector $systemNeuron
     */
    private $dataCollector;
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
     * @param DataCollector $dataCollector
     * @param Lesson $lesson
     * @param LearningMetadata $learningMetadata
     * @param bool $hasCompleted
     * @param bool $isAvailable
     */
    public function __construct(
        DataCollector $dataCollector,
        Lesson $lesson,
        LearningMetadata $learningMetadata,
        bool $hasCompleted,
        bool $isAvailable
    ) {
        $this->dataCollector = $dataCollector;
        $this->lesson = $lesson->getId();
        $this->lessonObject = $lesson;
        $this->learningMetadata = $learningMetadata;
        $this->hasCompleted = $hasCompleted;
        $this->isAvailable = $isAvailable;
    }
    /**
     * @param int $id
     * @return LearningLesson
     */
    public function setId(int $id): LearningLesson
    {
        $this->id = $id;

        return $this;
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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