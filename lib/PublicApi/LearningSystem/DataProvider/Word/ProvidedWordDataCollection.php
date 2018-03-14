<?php

namespace PublicApi\LearningSystem\DataProvider\Word;

use PublicApi\LearningSystem\DataProvider\BaseProvidedDataCollection;

class ProvidedWordDataCollection extends BaseProvidedDataCollection
{
    /**
     * @var ProvidedWord[] $words
     */
    private $words;
    /**
     * ProvidedWordDataCollection constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);

        foreach ($data as $arrayWord) {
            $this->words[] = new ProvidedWord($arrayWord);
        }
    }
    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->words);
    }
}