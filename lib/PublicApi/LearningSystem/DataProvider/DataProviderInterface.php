<?php

namespace PublicApi\LearningSystem\DataProvider;

interface DataProviderInterface
{
    /**
     * @return array
     */
    public function getData(): array;
}