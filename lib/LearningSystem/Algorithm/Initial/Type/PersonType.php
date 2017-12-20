<?php

namespace LearningSystem\Algorithm\Initial\Type;

use LearningSystem\Infrastructure\Type\BaseType;

class PersonType extends BaseType
{
    /**
     * @var array $types
     */
    protected static $types = [
        0 => 'risk_taker',
        1 => 'sure_thing',
        2 => 'between',
    ];
}