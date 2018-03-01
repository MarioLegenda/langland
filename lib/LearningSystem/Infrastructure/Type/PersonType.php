<?php

namespace LearningSystem\Infrastructure\Type;

class PersonType extends BaseType implements NamedTypeInterface
{
    /**
     * @var array $types
     */
    protected static $types = [
        'risk_taker',
        'sure_thing',
    ];
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'person_type';
    }
    /**
     * @return string
     */
    public function __toString(): string
    {
        return static::getName();
    }
}