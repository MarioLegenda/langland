<?php

namespace LearningSystem\Algorithm\Initial\Parameter;

use LearningSystem\Algorithm\Initial\Parameter\Contract\AlgorithmParameterInterface;

class BaseAlgorithmParameter implements AlgorithmParameterInterface
{
    /**
     * @var array $metadata
     */
    protected $metadata = [];
    /**
     * @var string $name
     */
    protected $name;
    /**
     * @var bool $isProcessed
     */
    protected $isProcessed = false;
    /**
     * @inheritdoc
     */
    public function getMetadata(): array
    {
        if (empty($this->metadata)) {
            $message = sprintf('Metadata should not be empty for algorithm parameter %s', get_class($this));
            throw new \LogicException($message);
        }

        return $this->metadata;
    }
    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return $this->isProcessed;
    }
}