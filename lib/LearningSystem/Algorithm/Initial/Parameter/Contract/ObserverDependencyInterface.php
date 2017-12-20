<?php

namespace LearningSystem\Algorithm\Initial\Parameter\Contract;

interface ObserverDependencyInterface
{
    /**
     * @return bool
     */
    public function hasDependencies(): bool;
    /**
     * @return array
     */
    public function getDependencies(): array;
}