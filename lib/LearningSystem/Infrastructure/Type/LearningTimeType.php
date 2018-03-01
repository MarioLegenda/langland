<?php

namespace LearningSystem\Infrastructure\Type;

class LearningTimeType extends BaseType implements NamedTypeInterface
{
    /**
     * @var array $types
     */
    protected static $types = [
        'morning',
        'evening',
        'early_afternoon',
        'late_afternoon',
        'night',
    ];
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'learning_time';
    }
    /**
     * @return string
     */
    public function __toString(): string
    {
        return static::getName();
    }
}