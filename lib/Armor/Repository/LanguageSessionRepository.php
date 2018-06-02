<?php

namespace Armor\Repository;

use ArmorBundle\Entity\LanguageSession;
use ArmorBundle\Entity\User;
use Library\Infrastructure\Repository\CommonRepository;
use PublicApiBundle\Entity\LearningUser;

class LanguageSessionRepository extends CommonRepository
{
    /**
     * @param LanguageSession $languageSession
     * @return LanguageSession
     */
    public function persistAndFlush(LanguageSession $languageSession): LanguageSession
    {
        $this->persist($languageSession)->flush();

        return $languageSession;
    }
    /**
     * @param LearningUser $learningUser
     * @param User $user
     * @return LanguageSession
     */
    public function findByLearningUserAndUser(LearningUser $learningUser, User $user): LanguageSession
    {
        /** @var LanguageSession $languageSession */
        $languageSession = $this->findOneBy([
            'learningUser' => $learningUser,
            'user' => $user,
        ]);

        return $languageSession;
    }
}