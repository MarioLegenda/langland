<?php

namespace LearningSystem\Library;

interface DataProviderInterface
{
    /**
     * @param array $rules
     * @return ProvidedDataInterface
     */
    public function getData(array $rules): ProvidedDataInterface;
}