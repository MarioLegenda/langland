<?php

namespace LearningMetadata\Infrastructure\Type;

class CourseType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        'Beginner',
        'Intermediate',
        'Advanced',
    ];
}