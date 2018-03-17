<?php

namespace LearningSystem\Library;

use Library\Infrastructure\Notation\ArrayNotationInterface;

interface ProvidedDataInterface extends \IteratorAggregate, \Countable, ArrayNotationInterface
{
    /**
     * @param string $field
     * @return mixed
     */
    public function getField(string $field);
    /**
     * @param string $field
     * @return mixed
     */
    public function hasField(string $field);
    /**
     * @param array $toExclude
     * @return mixed
     */
    public function getFields(array $toExclude = []);
}