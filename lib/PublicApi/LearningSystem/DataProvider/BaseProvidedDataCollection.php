<?php

namespace PublicApi\LearningSystem\DataProvider;

use LearningSystem\Library\ProvidedDataInterface;

class BaseProvidedDataCollection implements ProvidedDataInterface
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

    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    public function getField(string $field)
    {
        $message = sprintf('getField() is not implemented for %s', BaseProvidedDataCollection::class);
        throw new \RuntimeException($message);
    }
    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    public function getFields(array $toExclude = [])
    {
        $message = sprintf('getFields() is not implemented for %s', BaseProvidedDataCollection::class);
        throw new \RuntimeException($message);
    }
    /**
     * @inheritdoc
     * @throws \RuntimeException
     */
    public function hasField(string $field)
    {
        $message = sprintf('hasField() is not implemented for %s', BaseProvidedDataCollection::class);
        throw new \RuntimeException($message);
    }
}