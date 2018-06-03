<?php

namespace LearningMetadata\Repository\Implementation;

use AdminBundle\Entity\Language;
use AdminBundle\Entity\Lesson;
use Doctrine\ORM\EntityRepository;

class LessonRepository extends EntityRepository
{
    /**
     * @param Lesson $lesson
     * @return Lesson
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function persistAndFlush(Lesson $lesson): Lesson
    {
        $this->getEntityManager()->persist($lesson);
        $this->getEntityManager()->flush();

        return $lesson;
    }
}