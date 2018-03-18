<?php

namespace PublicApi\LearningSystem\Business\Implementation;

use PublicApi\Infrastructure\Type\TypeInterface;
use PublicApi\Language\Infrastructure\LanguageProvider;
use PublicApi\LearningSystem\Repository\LearningMetadataRepository;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;

class LearningMetadataImplementation
{
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
     * @param LanguageProvider $languageProvider
     * @param LearningUserProvider $learningUserProvider
     * @param LearningMetadataRepository $learningMetadataRepository
     */
    public function __construct(
        LanguageProvider $languageProvider,
        LearningUserProvider $learningUserProvider,
        LearningMetadataRepository $learningMetadataRepository
    ) {
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
}