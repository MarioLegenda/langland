<?php

namespace AppBundle\Entity;

class LearningUserPreference
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var int $investingTime
     */
    private $investingTime;
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
     * Set investingTime
     *
     * @param integer $investingTime
     *
     * @return LearningUserPreference
     */
    public function setInvestingTime(int $investingTime) : LearningUserPreference
    {
        $this->investingTime = $investingTime;

        return $this;
    }

    /**
     * Get investingTime
     *
     * @return int
     */
    public function getInvestingTime()
    {
        return $this->investingTime;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LearningUserPreference
     */
    public function setCreatedAt(\DateTime $createdAt) : LearningUserPreference
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
     * @return LearningUserPreference
     */
    public function setUpdatedAt(\DateTime $updatedAt) : LearningUserPreference
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

