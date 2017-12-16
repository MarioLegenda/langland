<?php

namespace LearningSystem\Algorithm\Initial\Type;

use LearningSystem\Infrastructure\Type\BaseType;

class MemoryType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        0 => 'short_term',
        1 => 'long_term',
        2 => 'in_between',
    ];
}