<?php

namespace PublicApi\LearningSystem\DataProvider\Word;

use LearningSystem\Library\ProvidedDataInterface;

class ProvidedWord implements ProvidedDataInterface
{
    /**
     * @var array $word
     */
    private $word;
    /**
     * ProvidedWord constructor.
     * @param array $word
     */
    public function __construct(array $word)
    {
        $this->word = $word;
    }
    /**
     * @param string $field
     * @return mixed|null
     */
    public function getField(string $field)
    {
        if ($this->hasField($field)) {
            return $this->word[$field];
        }

        return null;
    }
    /**
     * @param array $toExclude
     * @return array
     */
    public function getFields(array $toExclude = []): array
    {
        $fields = array_keys($this->word);

        foreach ($toExclude as $excluded) {
            unset($fields[array_search($excluded, $fields)]);
        }

        sort($fields);

        return $fields;
    }
    /**
     * @param string $field
     * @return bool
     */
    public function hasField(string $field): bool
    {
        return array_key_exists($field, $this->word);
    }
    /**
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->getField('translations');
    }
    /**
     * @return array
     */
    public function getFalseTranslations(): array
    {
        return $this->getField('false_translations');
    }
    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->word);
    }
    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->getFields());
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->word;
    }
}