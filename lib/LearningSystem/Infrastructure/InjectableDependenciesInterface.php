<?php

namespace LearningSystem\Infrastructure;

interface InjectableDependenciesInterface
{
    /**
     * @param string $key
     * @param $dependency
     */
    public function inject(string $key, $dependency): void;
}