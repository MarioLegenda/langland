<?php

namespace AdminBundle\Repository;

use AdminBundle\Entity\Course;
use Doctrine\ORM\EntityRepository;

class WordGameRepository extends EntityRepository
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
            ->setParameter(':course_id', $course->getId())
            ->getQuery()
            ->getResult();

        return $result;
    }

    public function findSingleGameByCourse(Course $course, $gameId)
    {
        $qb = $this->createQueryBuilder('g');

        $result = $qb
            ->innerJoin('g.lesson', 'l')
            ->innerJoin('l.course', 'c')
            ->where('l.id = g.lesson')
            ->andWhere('l.course = :course_id')
            ->andWhere('g.id = :game_id')
            ->setParameter(':game_id', $gameId)
            ->setParameter(':course_id', $course->getId())
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }
}