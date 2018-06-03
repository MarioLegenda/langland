<?php

namespace PublicApi\Language\Business\Implementation;

use AdminBundle\Entity\Language;
use AdminBundle\Entity\LanguageInfo;
use ApiSDK\ApiSDK;
use ArmorBundle\Entity\User;
use Library\Infrastructure\Helper\SerializerWrapper;
use PublicApi\Infrastructure\Communication\RepositoryCommunicator;
use PublicApi\Language\Infrastructure\Model\LanguagePresentation;
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
     * @var SerializerWrapper $serializerWrapper
     */
    private $serializerWrapper;
    /**
     * @var ApiSDK $apiSDK
     */
    private $apiSDK;
    /**
     * LanguageImplementation constructor.
     * @param LanguageRepository $languageRepository
     * @param RepositoryCommunicator $repositoryCommunicator
     * @param LanguageInfoRepository $languageInfoRepository
     * @param SerializerWrapper $serializerWrapper
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        LanguageRepository $languageRepository,
        RepositoryCommunicator $repositoryCommunicator,
        LanguageInfoRepository $languageInfoRepository,
        SerializerWrapper $serializerWrapper,
        ApiSDK $apiSDK
    ) {
        $this->languageRepository = $languageRepository;
        $this->repositoryCommunicator = $repositoryCommunicator;
        $this->languageInfoRepository = $languageInfoRepository;
        $this->serializerWrapper = $serializerWrapper;
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
    public function createLanguagePresentation(User $user): array
    {
        $sortedLanguages = $this->repositoryCommunicator->getSortedLanguages($user);

        /** @var LanguagePresentation[] $data */
        $languagePresentations = $this->createLanguagePresentationModels($sortedLanguages);

        $serialized = $this->serializerWrapper->serializeMany(
            $languagePresentations,
            ['language_presentation']
        );

        $presentationLanguagesArray = json_decode($serialized, true);

        $response = $this->apiSDK
            ->create($presentationLanguagesArray)
            ->isCollection()
            ->setStatusCode(200)
            ->method('GET')
            ->build();

        return $response;
    }
    /**
     * @param Language $language
     * @return array
     */
    public function findLanguageInfo(Language $language): array
    {
        /** @var LanguageInfo $languageInfo */
        $languageInfo = $this->languageInfoRepository->findOneBy([
            'language' => $language
        ]);

        $serialized = $this->serializerWrapper->serialize($languageInfo, ['language_info'], 'json');

        $data = $this->apiSDK
            ->create(json_decode($serialized, true))
            ->method('GET')
            ->setStatusCode(200)
            ->isResource()
            ->build();

        return $data;
    }

    /**
     * @param Language[] $sortedLanguages
     * @return LanguagePresentation[]
     */
    private function createLanguagePresentationModels(array $sortedLanguages): array
    {
        $languagePresentations = [];

        /** @var Language $language */
        foreach ($sortedLanguages['alreadyLearning'] as $language) {
            /** @var LanguagePresentation $languagePresentation */
            $languagePresentation = $this->serializerWrapper->convertFromTo(
                $language,
                ['viewable'],
                LanguagePresentation::class,
                false
            );

            $languagePresentation->parseUrls();
            $languagePresentation->setAlreadyLearning();

            $this->serializerWrapper->getModelValidator()->validate($languagePresentation);

            $languagePresentations[] = $languagePresentation;
        }

        /** @var Language $language */
        foreach ($sortedLanguages['notLearning'] as $language) {
            /** @var LanguagePresentation $languagePresentation */
            $languagePresentation = $this->serializerWrapper->convertFromTo(
                $language,
                ['viewable'],
                LanguagePresentation::class,
                false
            );

            $languagePresentation->parseUrls();

            $this->serializerWrapper->getModelValidator()->validate($languagePresentation);

            $languagePresentations[] = $languagePresentation;
        }

        return $languagePresentations;
    }
}