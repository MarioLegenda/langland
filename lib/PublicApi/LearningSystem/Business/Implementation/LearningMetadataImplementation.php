<?php

namespace PublicApi\LearningSystem\Business\Implementation;

use ApiSDK\ApiSDK;
use PublicApi\Language\Infrastructure\LanguageProvider;
use PublicApi\LearningSystem\Repository\LearningMetadataRepository;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
use PublicApiBundle\Entity\LearningLesson;
use Ramsey\Uuid\Uuid;

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
     * @return LearningLesson
     */
    public function createLearningMetadata(): LearningLesson
    {
        $learningUser = $this->learningUserProvider->getLearningUser();
        $language = $this->languageProvider->getLanguage();

        return $this->learningMetadataRepository->createLearningMetadataForAllLessonsByLanguage(
            $language,
            $learningUser
        );
    }
    /**
     * @return array
     */
    public function getLearningLessonPresentation(): array
    {
        $learningUser = $this->learningUserProvider->getLearningUser();
        $language = $this->languageProvider->getLanguage();

        $presentation = $this->learningMetadataRepository->getLearningLessonPresentation(
            $learningUser,
            $language
        );

        return $this->apiSdk
            ->create($presentation)
            ->isCollection()
            ->method('GET')
            ->setStatusCode(200)
            ->setCacheKey(Uuid::uuid4()->toString())
            ->build();
    }
    /**
     * @return array
     */
    public function getLearningGamesPresentation(): array
    {
        $learningUserId = $this->learningUserProvider->getLearningUser()->getId();
        $languageId = $this->languageProvider->getLanguage()->getId();

        $presentation = $this->learningMetadataRepository->getLearningGamesPresentation(
            $learningUserId,
            $languageId
        );

        return $this->apiSdk
            ->create($presentation)
            ->isCollection()
            ->method('GET')
            ->setStatusCode(200)
            ->setCacheKey(Uuid::uuid4()->toString())
            ->build();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getLearningLessonById(int $id): array
    {
        $learningLessonData = $this->learningMetadataRepository->getLearningLessonById($id);
        
        $language = $this->languageProvider->getLanguage();
        
        $learningLessonData['lesson_language_url'] = sprintf('langland/language/%s/%d', $language->getName(), $language->getId());

        return $this->apiSdk
            ->create($learningLessonData)
            ->isResource()
            ->method('GET')
            ->setStatusCode(200)
            ->build();
    }
    /**
     * @param int $learningMetadataId
     * @return array
     */
    public function getRunnableGameByLearningMetadataId(int $learningMetadataId): array
    {
        $learningGameData = $this->learningMetadataRepository->getRunnableGameByLearningMetadataId($learningMetadataId);


    }
}