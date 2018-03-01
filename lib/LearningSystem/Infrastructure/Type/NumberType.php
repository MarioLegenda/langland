<?php

namespace LearningSystem\Infrastructure\Type;

class NumberType extends BaseType
{
    /**
     * @param mixed $value
     * @return TypeInterface
     */
    public static function fromValue($value): TypeInterface
    {
        if (is_int($value)) {
            return new static([$value]);
        }

        throw new \RuntimeException(sprintf('%s could not be created from value %s', static::class, (string) $value));
    }
}