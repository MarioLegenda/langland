<?php

namespace Library\LearningMetadata\Repository\Implementation;

use AdminBundle\Entity\Course;
use Doctrine\ORM\EntityRepository;

class CourseRepository extends EntityRepository
{
    public function persistAndFlush(Course $course)
    {
        $this->getEntityManager()->persist($course);
        $this->getEntityManager()->flush($course);
    }
}