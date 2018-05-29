<?php

namespace PublicApiBundle\Entity;

class LearningMetadata
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
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * LearningMetadata constructor.
     * @param DataCollector $dataCollector
     */
    public function __construct(
        DataCollector $dataCollector
    ) {
        $this->dataCollector = $dataCollector;
    }
    /**
     * @return int
     */
    public function getId(): int
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