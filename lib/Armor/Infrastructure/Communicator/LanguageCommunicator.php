<?php

namespace Armor\Infrastructure\Communicator;

use LearningMetadata\Repository\Implementation\LanguageRepository;

class LanguageCommunicator
{
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * LanguageCommunicator constructor.
     * @param LanguageRepository $languageRepository
     */
    public function __construct(
        LanguageRepository $languageRepository
    ) {
        $this->languageRepository = $languageRepository;
    }


}