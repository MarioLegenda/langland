<?php

namespace PublicApi\LearningSystem\DataProvider\Word;

class ProvidedWord
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
}