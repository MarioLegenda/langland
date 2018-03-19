<?php

namespace PublicApi\LearningSystem\Business\Implementation;

use ApiSDK\ApiSDK;
use PublicApi\Infrastructure\Type\TypeInterface;
use PublicApi\Language\Infrastructure\LanguageProvider;
use PublicApi\LearningSystem\Repository\LearningMetadataRepository;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;

class LearningMetadataImplementation
{
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * @var LearningMetadataRepository $learningMetadataRepository
     */
    private $learningMetadataRepository;
    /**
     * @var LearningUserProvider $learningUserProvider
     */
    private $learningUserProvider;
    /**
     * @var LanguageProvider $languageProvider
     */
    private $languageProvider;
    /**
     * LearningMetadataImplementation constructor.
     * @param ApiSDK $apiSDK
     * @param LanguageProvider $languageProvider
     * @param LearningUserProvider $learningUserProvider
     * @param LearningMetadataRepository $learningMetadataRepository
     */
    public function __construct(
        ApiSDK $apiSDK,
        LanguageProvider $languageProvider,
        LearningUserProvider $learningUserProvider,
        LearningMetadataRepository $learningMetadataRepository
    ) {
        $this->apiSdk = $apiSDK;
        $this->learningMetadataRepository = $learningMetadataRepository;
        $this->learningUserProvider = $learningUserProvider;
        $this->languageProvider = $languageProvider;
    }
    /**
     * @param TypeInterface $courseType
     * @param int $courseLearningOrder
     * @param int $lessonLearningOrder
     * @return array
     */
    public function createLearningMetadata(
        TypeInterface $courseType,
        int $courseLearningOrder,
        int $lessonLearningOrder
    ): array {
        return $this->learningMetadataRepository->createLearningMetadata(
            $courseType,
            $courseLearningOrder,
            $lessonLearningOrder,
            $this->languageProvider->getLanguage()->getId(),
            $this->learningUserProvider->getLearningUser()->getId()
        );
    }
    /**
     * @return array
     */
    public function getLearningLessonPresentation(): array
    {
        $presentation = $this->learningMetadataRepository->getLearningLessonPresentation(
            $this->learningUserProvider->getLearningUser()->getId(),
            $this->languageProvider->getLanguage()->getId()
        );

        return $this->apiSdk
            ->create($presentation)
            ->isCollection()
            ->method('GET')
            ->setStatusCode(200)
            ->build();
    }
}