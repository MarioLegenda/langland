<?php

namespace PublicApi\LearningSystem\Business\Implementation;

use ApiSDK\ApiSDK;
use Armor\Infrastructure\Provider\LanguageSessionProvider;
use ArmorBundle\Entity\User;
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
     * @var LanguageSessionProvider $languageSessionProvider
     */
    private $languageSessionProvider;
    /**
     * LearningMetadataImplementation constructor.
     * @param ApiSDK $apiSDK
     * @param LanguageSessionProvider $languageSessionProvider
     * @param LearningMetadataRepository $learningMetadataRepository
     */
    public function __construct(
        ApiSDK $apiSDK,
        LanguageSessionProvider $languageSessionProvider,
        LearningMetadataRepository $learningMetadataRepository
    ) {
        $this->apiSdk = $apiSDK;
        $this->learningMetadataRepository = $learningMetadataRepository;
        $this->languageSessionProvider = $languageSessionProvider;
    }
    /**
     * @return LearningLesson
     */
    public function createLearningLessons(): LearningLesson
    {
        $learningUser = $this->languageSessionProvider->getLearningUser();
        $language = $this->languageSessionProvider->getLanguage();

        return $this->learningMetadataRepository->createAllLearningLessonsForLearningUser(
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