<?php

namespace LearningSystemBundle\Entity;

class SystemHead
{
    /**
     * @var int $id
     */
    private $id;
    /**
     * @var int $externalCorrelationId
     */
    private $externalCorrelationId;
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
    public function getExternalCorrelationId(): int
    {
        return $this->externalCorrelationId;
    }
    /**
     * @param int $externalCorrelationId
     * @return SystemHead
     */
    public function setExternalCorrelationId(int $externalCorrelationId): SystemHead
    {
        $this->externalCorrelationId = $externalCorrelationId;
    }


}