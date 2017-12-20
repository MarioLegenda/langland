<?php

namespace LearningSystem\Algorithm\Initial\Type;

use LearningSystem\Infrastructure\Type\BaseType;

class FreeTimeType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        0 => '15 minutes',
        1 => '30 minutes',
        2 => '1 hour',
        3 => '1 hour and 30 minutes',
        4 => '2 hours',
    ];
}