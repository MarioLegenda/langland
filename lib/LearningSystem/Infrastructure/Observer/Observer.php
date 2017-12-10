<?php

namespace LearningSystem\Infrastructure\Observer;

interface Observer
{
    /**
     * @param Subject $subject
     * @param array $dependencies
     */
    public function update(Subject $subject, array $dependencies = []): void;
}