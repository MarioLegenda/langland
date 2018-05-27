<?php

namespace PublicApi\Infrastructure\Model\Word;

class InitialCreationWord
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var int $level
     */
    private $level;
    /**
     * @var \DateTime $createdAt
     */
    private $createdAt;
    /**
     * @var \DateTime $updatedAt
     */
    private $updatedAt;
    /**
     * InitialCreationWord constructor.
     * @param int $id
     * @param int $level
     * @param \DateTime $createdAt
     * @param \DateTime|null $updatedAt
     */
    public function __construct(
        int $id,
        int $level,
        \DateTime $createdAt,
        \DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->level = $level;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return int
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }
    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}