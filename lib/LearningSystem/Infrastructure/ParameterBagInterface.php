<?php

namespace LearningSystem\Infrastructure;

interface ParameterBagInterface
{
    /**
     * @param string $key
     * @param $parameter
     * @return ParameterBagInterface
     */
    public function add(string $key, $parameter): ParameterBagInterface;
    /**
     * @param string $key
     * @return bool
     */
    public function remove(string $key): bool;
    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;
    /**
     * @param string $key
     * @param mixed $default
     * @return array|null
     */
    public function get(string $key, $default = null): ?array;
    /**
     * @return bool
     */
    public function isEmpty(): bool;
}