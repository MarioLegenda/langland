<?php

namespace LearningSystem\Infrastructure\Type;

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