<?php

namespace PublicApi\LearningUser\Repository;

use Library\Infrastructure\Repository\CommonRepository;
use PublicApiBundle\Entity\LearningUser;

class LearningUserRepository extends CommonRepository
{
    /**
     * @param LearningUser $learningUser
     * @return LearningUser
     */
    public function persistAndFlush(LearningUser $learningUser): LearningUser
    {
        $this->em->persist($learningUser);
        $this->em->flush();

        return $learningUser;
    }
}