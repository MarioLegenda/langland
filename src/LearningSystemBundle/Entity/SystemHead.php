<?php

namespace LearningSystemBundle\Entity;

use LearningSystem\Library\Repository\Contract\SystemHeadInterface;

class SystemHead implements SystemHeadInterface
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
     * @inheritdoc
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @inheritdoc
     */
    public function setId(int $id): SystemHeadInterface
    {
        $this->id = $id;
    }
    /**
     * @inheritdoc
     */
    public function getExternalCorrelationId(): int
    {
        return $this->externalCorrelationId;
    }
    /**
     * @inheritdoc
     */
    public function setExternalCorrelationId(int $externalCorrelationId): SystemHeadInterface
    {
        $this->externalCorrelationId = $externalCorrelationId;
    }


}