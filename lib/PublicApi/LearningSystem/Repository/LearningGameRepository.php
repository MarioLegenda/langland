<?php

namespace PublicApi\LearningSystem\Repository;

use Library\Infrastructure\Repository\CommonRepository;
use PublicApiBundle\Entity\LearningGame;

class LearningGameRepository extends CommonRepository
{
    /**
     * @param LearningGame $learningGame
     * @return LearningGame
     */
    public function persistAndFlush(LearningGame $learningGame)
    {
        $this->em->persist($learningGame);
        $this->em->flush();

        return $learningGame;
    }
}