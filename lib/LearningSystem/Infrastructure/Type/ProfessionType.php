<?php

namespace LearningSystem\Infrastructure\Type;

class ProfessionType extends BaseType implements NamedTypeInterface
{
    /**
     * @var array $types
     */
    protected static $types = [
        'arts_and_entertainment',
        'business',
        'industrial_and_manufacturing',
        'law_enforcement_and_armed_forces',
        'science_and_technology',
        'healthcare_and_medicine',
        'service_oriented_occupation',
    ];
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'profession';
    }
    /**
     * @return string
     */
    public function __toString(): string
    {
        return static::getName();
    }
}