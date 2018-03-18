<?php

namespace LearningSystem\Library;

interface DataDeciderInterface
{
    /**
     * @param int $learningMetadataId
     * @return array
     */
    public function getData(int $learningMetadataId): array;
}