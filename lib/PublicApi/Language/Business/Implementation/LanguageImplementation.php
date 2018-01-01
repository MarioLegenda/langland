<?php

namespace PublicApi\Language\Business\Implementation;

use AdminBundle\Entity\Language;
use AdminBundle\Entity\LanguageInfo;
use ArmorBundle\Entity\User;
use PublicApi\Infrastructure\Communication\RepositoryCommunicator;
use PublicApi\Language\Repository\LanguageInfoRepository;
use PublicApi\Language\Repository\LanguageRepository;

class LanguageImplementation
{
    /**
     * @var LanguageInfoRepository $languageInfoRepository
     */
    private $languageInfoRepository;
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * @var RepositoryCommunicator $repositoryCommunicator
     */
    private $repositoryCommunicator;
    /**
     * LanguageImplementation constructor.
     * @param LanguageRepository $languageRepository
     * @param RepositoryCommunicator $repositoryCommunicator
     * @param LanguageInfoRepository $languageInfoRepository
     */
    public function __construct(
        LanguageRepository $languageRepository,
        RepositoryCommunicator $repositoryCommunicator,
        LanguageInfoRepository $languageInfoRepository
    ) {
        $this->languageRepository = $languageRepository;
        $this->repositoryCommunicator = $repositoryCommunicator;
        $this->languageInfoRepository = $languageInfoRepository;
    }
    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->languageRepository->findAll();
    }
    /**
     * @param User $user
     * @return array
     */
    public function findAllWithAlreadyLearning(User $user): array
    {
        return $this->repositoryCommunicator->getAllAlreadyLearningLanguages($user);
    }
    /**
     * @param Language $language
     * @return LanguageInfo
     */
    public function findLanguageInfo(Language $language): LanguageInfo
    {
        /** @var LanguageInfo $languageInfo */
        $languageInfo = $this->languageInfoRepository->findOneBy([
            'language' => $language
        ]);

        return $languageInfo;
    }
}