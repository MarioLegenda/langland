<?php

namespace LearningSystem\Library;

use PublicApiBundle\Entity\LearningLesson;

interface DataDeciderInterface
{
    /**
     * @param LearningLesson $learningLesson
     * @return array
     */
    public function getData(LearningLesson $learningLesson): array;
}