<?php

namespace PublicApi\Language\Business\Implementation;

use AdminBundle\Entity\Language;
use AdminBundle\Entity\LanguageInfo;
use ApiSDK\ApiSDK;
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
     * @var ApiSDK $apiSDK
     */
    private $apiSDK;
    /**
     * LanguageImplementation constructor.
     * @param LanguageRepository $languageRepository
     * @param RepositoryCommunicator $repositoryCommunicator
     * @param LanguageInfoRepository $languageInfoRepository
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        LanguageRepository $languageRepository,
        RepositoryCommunicator $repositoryCommunicator,
        LanguageInfoRepository $languageInfoRepository,
        ApiSDK $apiSDK
    ) {
        $this->languageRepository = $languageRepository;
        $this->repositoryCommunicator = $repositoryCommunicator;
        $this->languageInfoRepository = $languageInfoRepository;
        $this->apiSDK = $apiSDK;
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
        $languages = $this->repositoryCommunicator->getAllAlreadyLearningLanguages($user);

        $response = $this->apiSDK
            ->create($languages)
            ->isCollection()
            ->setStatusCode(200)
            ->method('GET')
            ->build();

        return $response;
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