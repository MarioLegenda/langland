<?php

namespace PublicApi\LearningUser\Repository;

use Library\Infrastructure\Repository\CommonRepository;
use PublicApi\Infrastructure\Model\Language;
use PublicApiBundle\Entity\LearningUser;
use PublicApiBundle\Entity\Question;

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
    /**
     * @return Question[]
     */
    public function getQuestions(): array
    {
        return $this->em->getRepository(Question::class)->findAll();
    }
}