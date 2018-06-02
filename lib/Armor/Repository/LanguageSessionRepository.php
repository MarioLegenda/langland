<?php

namespace Armor\Repository;

use Armor\Infrastructure\Model\Language;
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
    /**
     * @param Language $language
     * @param User $user
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function tryFindByLanguageAndUser(Language $language, User $user)
    {
        $qb = $this->createQueryBuilderFromClass('ls');

        $languageSession = $qb
            ->innerJoin('ls.learningUser', 'lu')
            ->where('lu.language = :language_id')
            ->andWhere('ls.learningUser = lu.id')
            ->andWhere('ls.user = :user')
            ->setParameters([
                ':language_id' => $language->getId(),
                ':user' => $user,
            ])
            ->getQuery()
            ->getOneOrNullResult();

        return $languageSession;
    }
}