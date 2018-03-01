<?php

namespace LearningSystem\Infrastructure\Type;

interface NamedTypeInterface
{
    /**
     * @return string
     */
    public static function getName(): string;
}