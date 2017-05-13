<?php

namespace AppBundle\Repository;

use AppBundle\Entity\LearningUser;
use Doctrine\ORM\EntityRepository;

class LearningUserCourseRepository extends EntityRepository
{
    /**
     * @param LearningUser $learningUser
     * @return array
     */
    public function findCoursesByLearningUserDesc(LearningUser $learningUser)
    {
        $qb = $this->createQueryBuilder('luc');

        $result = $qb
            ->select('luc', 'c')
            ->innerJoin('luc.course', 'c')
            ->innerJoin('luc.learningUser', 'lu')
            ->andWhere('luc.learningUser = :learning_user_id')
            ->addOrderBy('c.initialCourse', 'DESC')
            ->setParameter(':learning_user_id', $learningUser->getId())
            ->getQuery()
            ->getResult();

        return $result;
    }
}
