<?php

namespace PublicApi\LearningSystem\Repository;

use Library\Infrastructure\Repository\CommonRepository;
use PublicApiBundle\Entity\LearningLesson;
use PublicApiBundle\Entity\LearningUser;

class LearningLessonRepository extends CommonRepository
{
    /**
     * @param LearningLesson $learningLesson
     * @return LearningLesson
     */
    public function persistAndFlush(LearningLesson $learningLesson)
    {
        $this->em->persist($learningLesson);
        $this->em->flush();

        return $learningLesson;
    }
}