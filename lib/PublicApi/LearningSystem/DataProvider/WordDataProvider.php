<?php

namespace PublicApi\LearningSystem\DataProvider;

use PublicApi\Language\Infrastructure\LanguageProvider;

class WordDataProvider implements DataProviderInterface
{
    /**
     * @var LanguageProvider $languageProvider
     */
    private $languageProvider;
    /**
     * WordDataProvider constructor.
     * @param LanguageProvider $languageProvider
     */
    public function __construct(
        LanguageProvider $languageProvider
    ) {
        $this->languageProvider = $languageProvider;
    }
    /**
     * @inheritdoc
     */
    public function getData(): array
    {

    }
}