<?php

namespace LearningSystem\Infrastructure\Type;

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