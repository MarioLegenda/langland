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
     * @throws \Doctrine\ORM\OptimisticLockException
     * @return LanguageSession
     */
    public function createAndRegisterLanguageSession(
        DomainCommunicatorInterface $domainCommunicator,
        User $user
    ): LanguageSession {

        $language = $domainCommunicator->getDomainModel();

        $learningUser = new LearningUser();
        $learningUser->setLanguage($language);
        $learningUser->setUser($user);

        $this->learningUserRepository->persistAndFlush($learningUser);

        $languageSession = LanguageSession::create($learningUser, $user);

        $this->languageSessionRepository->persistAndFlush($languageSession);

        $user->setCurrentLanguageSession($languageSession);
        $this->userRepository->persistAndFlush($user);

        return $languageSession;
    }
    /**
     * @param Language $language
     * @param User $user
     * @return LanguageSession|null
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateLanguageSessionIfUpdateable(Language $language, User $user): ?LanguageSession
    {
        /** @var LearningUser $learningUser */
        $learningUser = $this->learningUserRepository->findOneBy([
            'language' => $language,
            'user' => $user,
        ]);

        if (!$learningUser instanceof LearningUser) {
            return null;
        }

        $languageSession = $this->languageSessionRepository->findByLearningUserAndUser($learningUser, $user);

        $user->setCurrentLanguageSession($languageSession);

        $this->userRepository->persistAndFlush($user);

        return $languageSession;
    }
}