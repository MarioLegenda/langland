<?php

namespace LearningSystem\Infrastructure;

trait BaseInjectableDependencies
{
    /**
     * @var array $dependencies
     */
    protected $dependencies = [];
    /**
     * @param string $key
     * @param $dependency
     */
    public function inject(string $key, $dependency): void
    {
        $this->dependencies[$key] = $dependency;
    }
    /**
     * @param string $key
     * @return bool
     */
    private function has(string $key): bool
    {
        return array_key_exists($key, $this->dependencies);
    }
    /**
     * @param string $key
     * @return bool
     */
    public function remove(string $key): bool
    {
        if (!$this->has($key)) {
            return false;
        }

        return true;
    }
    /**
     * @void
     */
    public function clear(): void
    {
        $this->dependencies = [];
    }
}