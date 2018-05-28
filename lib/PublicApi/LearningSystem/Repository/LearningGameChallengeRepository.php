<?php

namespace PublicApi\LearningSystem\Repository;

use Library\Infrastructure\Repository\CommonRepository;
use PublicApiBundle\Entity\LearningGameChallenge;

class LearningGameChallengeRepository extends CommonRepository
{
    /**
     * @param LearningGameChallenge $learningGameChallenge
     * @return LearningGameChallenge
     */
    public function persistAndFlush(LearningGameChallenge $learningGameChallenge)
    {
        $this->em->persist($learningGameChallenge);
        $this->em->flush();

        return $learningGameChallenge;
    }
}