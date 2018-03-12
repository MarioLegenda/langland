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
     * @var Language $language
     */
    private $language;
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
        return $this->saveAndGetLanguage();
    }
    /**
     * @return Language
     */
    private function saveAndGetLanguage(): Language
    {
        if ($this->language instanceof Language) {
            return $this->language;
        }

        $this->language = $this->learningUserProvider->getLearningUser()->getLanguage();

        return $this->language;
    }
}