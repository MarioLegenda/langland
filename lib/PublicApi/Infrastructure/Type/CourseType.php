<?php

namespace PublicApi\Infrastructure\Type;


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