<?php

namespace PublicApi\LearningUser\Business\Implementation;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
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
     * LearningUserImplementation constructor.
     * @param LearningUserRepository $learningUserRepository
     * @param LanguageRepository $languageRepository
     */
    public function __construct(
        LearningUserRepository $learningUserRepository,
        LanguageRepository $languageRepository
    ) {
        $this->learningUserRepository = $learningUserRepository;
        $this->languageRepository = $languageRepository;
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

        return $this->learningUserRepository->persistAndFlush($learningUser);
    }
}