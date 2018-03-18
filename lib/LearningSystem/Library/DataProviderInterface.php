<?php

namespace LearningSystem\Library;

interface DataProviderInterface
{
    /**
     * @param int $learningMetadataId
     * @param array $rules
     * @return ProvidedDataInterface
     */
    public function getData(int $learningMetadataId, array $rules): ProvidedDataInterface;
}