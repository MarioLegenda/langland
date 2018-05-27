<?php

namespace Library\Infrastructure;

use LearningSystem\Library\ProvidedDataInterface;

class IterableObject implements ProvidedDataInterface
{
    /**
     * @var array $data
     */
    protected $data;
    /**
     * BaseProvidedDataCollection constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }
}