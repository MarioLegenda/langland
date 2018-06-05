<?php

namespace Armor\Domain;

use Armor\Infrastructure\Model\Language;
use Armor\Repository\LanguageSessionRepository;
use ArmorBundle\Entity\LanguageSession;
use Library\Infrastructure\Logic\DomainCommunicatorInterface;
use PublicApi\LearningUser\Repository\LearningUserRepository;
use ArmorBundle\Entity\User;
use ArmorBundle\Repository\UserRepository;
use PublicApiBundle\Entity\LearningUser;

class LanguageSessionLogic
{
    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;
    /**
     * @var LearningUserRepository $learningUserRepository
     */
    private $learningUserRepository;
    /**
     * @var LanguageSessionRepository $languageSessionRepository
     */
    private $languageSessionRepository;
    /**
     * LanguageSessionLogic constructor.
     * @param UserRepository $userRepository
     * @param LearningUserRepository $learningUserRepository
     * @param LanguageSessionRepository $languageSessionRepository
     */
    public function __construct(
        UserRepository $userRepository,
        LearningUserRepository $learningUserRepository,
        LanguageSessionRepository $languageSessionRepository
    ) {
        $this->userRepository = $userRepository;
        $this->learningUserRepository = $learningUserRepository;
        $this->languageSessionRepository = $languageSessionRepository;
    }
    /**
     * @param DomainCommunicatorInterface $domainCommunicator
     * @param User $user
     * @return LanguageSession
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createAndRegisterLanguageSession(
        DomainCommunicatorInterface $domainCommunicator,
        User $user
    ): LanguageSession {
        $language = $domainCommunicator->getForeignDomainModel();
        /** @var Language $domainLanguage */
        $domainLanguage = $domainCommunicator->getDomainModel();

        /** @var LanguageSession $languageSession */
        $languageSession = $this->languageSessionRepository->tryFindByLanguageAndUser(
            $domainLanguage,
            $user
        );

        if ($languageSession instanceof LanguageSession) {
            throw new \RuntimeException('Language session already exists');
        }

        $learningUser = new LearningUser();
        $learningUser->setLanguage($language);
        $learningUser->setUser($user);

        $this->learningUserRepository->persistAndFlush($learningUser);

        $languageSession = LanguageSession::create($learningUser, $user);

        $user->setCurrentLanguageSession($languageSession);
        $user->addLanguageSession($languageSession);

        $this->userRepository->persistAndFlush($user);

        return $languageSession;
    }
    /**
     * @param LanguageSession $languageSession
     * @param User $user
     * @return LanguageSession
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changeLanguageSession(LanguageSession $languageSession, User $user): LanguageSession
    {
        $user->setCurrentLanguageSession($languageSession);

        $this->userRepository->persistAndFlush($user);

        return $languageSession;
    }

    public function getCurrentLanguageSession(User $user)
    {
        $languageSession = $user->getCurrentLanguageSession();


    }
}