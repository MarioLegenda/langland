<?php

namespace PublicApi\LearningSystem\DataDecider;

interface DataDeciderInterface
{
    /**
     * @return array
     */
    public function getData(): array;
}