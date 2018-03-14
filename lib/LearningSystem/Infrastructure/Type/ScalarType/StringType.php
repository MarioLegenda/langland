<?php

namespace LearningSystem\Infrastructure\Type\ScalarType;

use LearningSystem\Infrastructure\Type\BaseType;
use LearningSystem\Infrastructure\Type\TypeInterface;

class StringType extends BaseType
{
    /**
     * @param mixed $value
     * @return TypeInterface
     */
    public static function fromValue($value): TypeInterface
    {
        if (is_string($value)) {
            return new static([$value]);
        }

        throw new \RuntimeException(sprintf('%s could not be created from value %s', static::class, (string) $value));
    }
}