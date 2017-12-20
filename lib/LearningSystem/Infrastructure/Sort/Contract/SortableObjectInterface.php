<?php

namespace LearningSystem\Infrastructure\Sort\Contract;

interface SortableObjectInterface
{
    /**
     * @return string
     */
    public function getMarker(): string;
}