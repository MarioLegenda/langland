<?php

namespace LearningSystem\Algorithm\Initial\Parameter\Contract;

interface AlgorithmParameterInterface
{
    /**
     * @return string
     */
    public function getName(): string;
    /**
     * @return array
     */
    public function getMetadata(): array;
    /**
     * @return bool
     */
    public function isProcessed(): bool;
}