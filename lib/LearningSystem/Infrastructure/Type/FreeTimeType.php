<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 01.03.18.
 * Time: 21:02
 */

namespace LearningSystem\Infrastructure\Type;


class FreeTimeType extends BaseType implements NamedTypeInterface
{
    protected static $types = [
        '30_minutes',
        '1_hour',
        '1_hour_and_a_half',
        '2_hours',
        'all_time',
    ];
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'free_time';
    }
    /**
     * @return string
     */
    public function __toString(): string
    {
        return static::getName();
    }
}