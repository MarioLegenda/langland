<?php

namespace PublicApi\LearningUser\Business\Implementation;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use ArmorBundle\Repository\UserRepository;
use PublicApi\Language\Repository\LanguageRepository;
use PublicApi\LearningUser\Repository\LearningUserRepository;
use PublicApiBundle\Entity\LearningUser;

class LearningUserImplementation
{
    /**
     * @var LearningUserRepository $learningUserRepository
     */
    private $learningUserRepository;
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;
    /**
     * LearningUserImplementation constructor.
     * @param LearningUserRepository $learningUserRepository
     * @param LanguageRepository $languageRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        LearningUserRepository $learningUserRepository,
        LanguageRepository $languageRepository,
        UserRepository $userRepository
    ) {
        $this->learningUserRepository = $learningUserRepository;
        $this->languageRepository = $languageRepository;
        $this->userRepository = $userRepository;
    }
    /**
     * @param int $id
     * @return null|LearningUser
     */
    public function tryFind(int $id): ?LearningUser
    {
        $learningUser = $this->learningUserRepository->find($id);

        if (!$learningUser instanceof LearningUser) {
            return null;
        }

        return $learningUser;
    }
    /**
     * @param int $id
     * @return LearningUser
     */
    public function find(int $id): LearningUser
    {
        $learningUser = $this->learningUserRepository->find($id);

        if (!$learningUser instanceof LearningUser) {
            throw new \RuntimeException('Learning user not found');
        }

        return $learningUser;
    }
    /**
     * @param int|Language $language
     * @param User $user
     * @return null|object
     */
    public function findExact($language, User $user)
    {
        if (is_int($language)) {
            $language = $this->languageRepository->find($language);
        }

        if (!$language instanceof Language) {
            throw new \RuntimeException('Not a valid language');
        }

        return $this->learningUserRepository->findOneBy([
            'language' => $language,
            'user' => $user,
        ]);
    }
    /**
     * @param Language $language
     * @param User $user
     * @return LearningUser
     */
    public function registerLearningUser(Language $language, User $user): LearningUser
    {
        $learningUser = new LearningUser();
        $learningUser->setLanguage($language);
        $learningUser->setUser($user);

        $user->setCurrentLearningUser($learningUser);

        $this->learningUserRepository->persistAndFlush($learningUser);

        $this->userRepository->persistAndFlush($user);

        return $learningUser;
    }
    /**
     * @param LearningUser $learningUser
     * @param User $user
     * @return LearningUser
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateLearningUser(LearningUser $learningUser, User $user): LearningUser
    {
        $this->userRepository->persistAndFlush($user);

        $user->setCurrentLearningUser($learningUser);

        return $learningUser;
    }
    /**
     * @param LearningUser $learningUser
     */
    public function markLanguageInfoLooked(LearningUser $learningUser)
    {
        if ($learningUser->getIsLanguageInfoLooked() === true) {
            return;
        }

        $learningUser->setIsLanguageInfoLooked(true);

        $this->learningUserRepository->persistAndFlush($learningUser);
    }
}