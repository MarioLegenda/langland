<?php

namespace PublicApi\Language\Infrastructure;

use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
use PublicApi\Infrastructure\Model\Language;

class LanguageProvider
{
    /**
     * @var LearningUserProvider $learningUserProvider
     */
    private $learningUserProvider;
    /**
     * LanguageProvider constructor.
     * @param LearningUserProvider $learningUserProvider
     */
    public function __construct(
        LearningUserProvider $learningUserProvider
    ) {
        $this->learningUserProvider = $learningUserProvider;
    }
    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        $language = $this->learningUserProvider->getLearningUser()->getLanguage();

        return new Language(
            $language->getId(),
            $language->getName(),
            $language->getCreatedAt(),
            $language->getUpdatedAt()
        );
    }
}