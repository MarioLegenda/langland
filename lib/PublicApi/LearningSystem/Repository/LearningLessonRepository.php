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
    /**
     * @param LearningUser $learningUser
     * @return array
     */
    public function getAllLearningLessonsByLearningUser(LearningUser $learningUser): array
    {
        $qb = $this->createQueryBuilderFromClass('ll');

        $learningLessons = $qb
            ->innerJoin('ll.learningMetadata', 'lm')
            ->where('ll.learningMetadata = lm.id')
            ->andWhere('ll.learningUser = :learningUser')
            ->setParameters([
                ':learningUser' => $learningUser,
            ])
            ->getQuery()
            ->getResult();

        return $learningLessons;
    }
}