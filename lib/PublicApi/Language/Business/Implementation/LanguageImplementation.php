<?php

namespace PublicApi\Language\Business\Implementation;

use PublicApi\Language\Repository\LanguageRepository;

class LanguageImplementation
{
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * LanguageImplementation constructor.
     * @param LanguageRepository $languageRepository
     */
    public function __construct(
        LanguageRepository $languageRepository
    ) {
        $this->languageRepository = $languageRepository;
    }
    /**
     * @return array
     */
    public function findAll()
    {
        return $this->languageRepository->findAll();
    }
}