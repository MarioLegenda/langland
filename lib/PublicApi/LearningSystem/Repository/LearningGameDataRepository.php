<?php

namespace PublicApi\LearningSystem\Repository;

use Library\Infrastructure\Repository\CommonRepository;
use PublicApiBundle\Entity\LearningGameData;

class LearningGameDataRepository extends CommonRepository
{
    /**
     * @param LearningGameData $learningGameData
     * @return LearningGameData
     */
    public function persistAndFlush(LearningGameData $learningGameData): LearningGameData
    {
        $this->persist($learningGameData);
        $this->flush();

        return $learningGameData;
    }
}