<?php

namespace PublicApi\LearningUser\Business\Implementation;

use AdminBundle\Entity\Language;
use ApiSDK\ApiSDK;
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
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * LearningUserImplementation constructor.
     * @param LearningUserRepository $learningUserRepository
     * @param LanguageRepository $languageRepository
     * @param UserRepository $userRepository
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        LearningUserRepository $learningUserRepository,
        LanguageRepository $languageRepository,
        UserRepository $userRepository,
        ApiSDK $apiSDK
    ) {
        $this->learningUserRepository = $learningUserRepository;
        $this->languageRepository = $languageRepository;
        $this->userRepository = $userRepository;
        $this->apiSdk = $apiSDK;
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
     * @return null|LearningUser
     */
    public function findExact($language, User $user): ?LearningUser
    {
        if (is_int($language)) {
            $language = $this->languageRepository->find($language);
        }

        if (!$language instanceof Language) {
            throw new \RuntimeException('Not a valid language');
        }

        /** @var LearningUser $learningUser */
        $learningUser = $this->learningUserRepository->findOneBy([
            'language' => $language,
            'user' => $user,
        ]);

        return $learningUser;
    }
    /**
     * @param Language $language
     * @param User $user
     * @return array
     */
    public function registerLearningUser(Language $language, User $user): array
    {
        $learningUser = new LearningUser();
        $learningUser->setLanguage($language);
        $learningUser->setUser($user);

        $user->setCurrentLearningUser($learningUser);

        $learningUser = $this->learningUserRepository->persistAndFlush($learningUser);

        $this->userRepository->persistAndFlush($user);

        $data = [
            'learningUser' => [
                'id' => $learningUser->getId()
            ],
            'language' => [
                'id' => $learningUser->getLanguage()->getId(),
                'name' => $learningUser->getLanguage()->getName(),
            ]
        ];

        return $this->apiSdk
            ->create($data)
            ->isResource()
            ->method('POST')
            ->setStatusCode(201)
            ->build();
    }
    /**
     * @param LearningUser $learningUser
     * @param User $user
     * @return array
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateLearningUser(LearningUser $learningUser, User $user): array
    {
        $this->userRepository->persistAndFlush($user);

        $user->setCurrentLearningUser($learningUser);

        $data = [
            'learningUser' => [
                'id' => $learningUser->getId()
            ],
            'language' => [
                'id' => $learningUser->getLanguage()->getId(),
                'name' => $learningUser->getLanguage()->getName(),
            ]
        ];

        return $this->apiSdk
            ->create($data)
            ->setStatusCode(200)
            ->isResource()
            ->method('POST')
            ->build();
    }
    /**
     * @param LearningUser $learningUser
     * @return array
     */
    public function markLanguageInfoLooked(LearningUser $learningUser): array
    {
        $data = [
            'isLanguageInfoLooked' => $learningUser->getIsLanguageInfoLooked(),
            'language' => [
                'id' => $learningUser->getLanguage()->getId(),
                'name' => $learningUser->getLanguage()->getName(),
            ]
        ];

        if ($learningUser->getIsLanguageInfoLooked() === false) {
            $learningUser->setIsLanguageInfoLooked(true);

            $data['isLanguageInfoLooked'] = $learningUser->getIsLanguageInfoLooked();

            $this->learningUserRepository->persistAndFlush($learningUser);
        }

        return $this->apiSdk
            ->create($data)
            ->setStatusCode(200)
            ->isResource()
            ->method('POST')
            ->build();
    }
    /**
     * @param LearningUser $learningUser
     * @return array
     */
    public function getIsLanguageInfoLooked(LearningUser $learningUser): array
    {
        $response = $this->apiSdk
            ->create(['isLanguageInfoLooked' => $learningUser->getIsLanguageInfoLooked()])
            ->setStatusCode(200)
            ->isResource()
            ->method('GET')
            ->build();

        return $response;
    }
}