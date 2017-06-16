<?php

namespace AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AdminBundle\Entity\Course;

class QuestionGameRepository extends EntityRepository
{
    /**
     * @param Course $course
     * @return array
     */
    public function findGamesByCourse(Course $course)
    {
        $qb = $this->createQueryBuilder('g');

        $result = $qb
            ->innerJoin('g.lesson', 'l')
            ->innerJoin('l.course', 'c')
            ->where('l.id = g.lesson')
            ->andWhere('l.course = :course_id')
            ->orderBy('g.id', 'DESC')
            ->setParameter(':course_id', $course->getId())
            ->getQuery()
            ->getResult();

        return $result;
    }
}