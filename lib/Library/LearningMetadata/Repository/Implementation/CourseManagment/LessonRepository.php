<?php

namespace Library\LearningMetadata\Repository\Implementation\CourseManagment;

use AdminBundle\Entity\Lesson;
use Doctrine\ORM\EntityRepository;

class LessonRepository extends EntityRepository
{
    /**
     * @param Lesson $lesson
     * @return Lesson
     */
    public function persistAndFlush(Lesson $lesson): Lesson
    {
        $this->getEntityManager()->persist($lesson);
        $this->getEntityManager()->flush();

        return $lesson;
    }
}