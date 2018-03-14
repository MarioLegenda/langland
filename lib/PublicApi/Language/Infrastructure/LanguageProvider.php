<?php

namespace PublicApi\Language\Infrastructure;


use AdminBundle\Entity\Language;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;

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
        return $this->learningUserProvider->getLearningUser()->getLanguage();
    }
}