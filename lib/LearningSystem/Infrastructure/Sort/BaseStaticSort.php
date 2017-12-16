<?php

namespace LearningSystem\Infrastructure\Sort;

use LearningSystem\Infrastructure\Sort\Type\ParameterBagSort;

class BaseStaticSort
{
    /**
     * @var BaseStaticSort|ParameterBagSort
     */
    protected static $instance;
    /**
     * @return BaseStaticSort
     */
    public static function init(): BaseStaticSort
    {
        static::$instance = (static::$instance instanceof static) ? static::$instance : new static();

        return static::$instance;
    }
    /**
     * BaseStaticSort constructor.
     */
    private function __construct(){}
}