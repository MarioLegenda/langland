<?php

namespace Library\LearningMetadata\Repository\Implementation;

use AdminBundle\Entity\Course;
use Doctrine\ORM\EntityRepository;

class CourseRepository extends EntityRepository
{
    /**
     * @param Course $course
     * @return Course
     */
    public function persistAndFlush(Course $course): Course
    {
        $this->getEntityManager()->persist($course);
        $this->getEntityManager()->flush($course);

        return $course;
    }
}