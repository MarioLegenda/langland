<?php

namespace LearningSystem\Library;

use PublicApiBundle\Entity\LearningLesson;

interface DataProviderInterface
{
    /**
     * @param LearningLesson $learningLesson
     * @param array $rules
     * @return ProvidedDataInterface
     */
    public function getData(LearningLesson $learningLesson, array $rules): ProvidedDataInterface;
}