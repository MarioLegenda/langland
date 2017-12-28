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
     * @param int $languageId
     * @param User $user
     */
    public function registerLearningUser(int $languageId, User $user)
    {
        $language = $this->languageRepository->find($languageId);

        if (!$language instanceof Language) {
            $message = sprintf('Language does not exist');
            throw new \RuntimeException($message);
        }

        $learningUser = $this->learningUserRepository->findOneBy([
            'user' => $user,
            'language' => $language,
        ]);

        if (!$learningUser instanceof LearningUser) {
            $learningUser = new LearningUser();
            $learningUser->setLanguage($language);
            $learningUser->setUser($user);

            $this->learningUserRepository->persistAndFlush($learningUser);
        }
    }
}