<?php

namespace LearningSystem\Infrastructure\Type;

class MemoryType extends BaseType implements NamedTypeInterface
{
    /**
     * @var array $types
     */
    protected static $types = [
        'short_term',
        'long_term',
        'in_between',
    ];
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'memory';
    }
    /**
     * @return string
     */
    public function __toString(): string
    {
        return static::getName();
    }
}