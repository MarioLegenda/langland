<?php

namespace AppBundle\Repository;

use AppBundle\Entity\LearningUserCourse;
use Doctrine\ORM\EntityRepository;

class LearningUserGameRepository extends EntityRepository
{
    public function findAvailableGamesByCourse(LearningUserCourse $course)
    {
        $qb = $this->createQueryBuilder('g');

        $result = $qb
            ->innerJoin('g.learningUserLesson', 'lul')
            ->innerJoin('lul.learningUserCourse', 'luc')
            ->where('lul.hasPassed = :hasPassed')
            ->andWhere('luc.id = lul.learningUserCourse')
            ->andWhere('g.learningUserLesson = lul.id')
            ->setParameter(':hasPassed', true)
            ->getQuery()
            ->getResult();

        return $result;
    }
}
