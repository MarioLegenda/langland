<?php

namespace LearningSystem\Library;

use Library\Infrastructure\Notation\ArrayNotationInterface;

interface ProvidedDataInterface extends \IteratorAggregate, \Countable, ArrayNotationInterface
{
}