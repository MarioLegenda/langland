<?php

namespace PublicApi\LearningSystem\DataProvider;

interface DataProviderInterface
{
    /**
     * @param array $rules
     * @return array
     */
    public function getData(array $rules): array;
}