<?php

namespace Tests\TestLibrary\DataProvider;

use AdminBundle\Entity\Language;
use Faker\Generator;
use Library\LearningMetadata\Repository\Implementation\LanguageRepository;

class LanguageDataProvider implements DefaultDataProviderInterface
{
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * LanguageDataProvider constructor.
     * @param LanguageRepository $languageRepository
     */
    public function __construct(
        LanguageRepository $languageRepository
    ) {
        $this->languageRepository = $languageRepository;
    }
    /**
     * @param Generator $faker
     * @return Language
     */
    public function createDefault(Generator $faker): Language
    {
        return $this->createLanguage(
            $faker->name,
            true,
            $faker->sentence(30)
        );
    }
    /**
     * @param Generator $faker
     * @return Language
     */
    public function createDefaultDb(Generator $faker): Language
    {
        return $this->languageRepository->persistAndFlush($this->createDefault($faker));
    }
    /**
     * @param string $name
     * @param bool $showOnPage
     * @param string $listDescription
     * @return Language
     */
    public function createLanguage(
        string $name,
        bool $showOnPage,
        string $listDescription
    ): Language {
        $language = new Language();
        $language->setName($name);
        $language->setShowOnPage($showOnPage);
        $language->setListDescription($listDescription);

        return $language;
    }
    /**
     * @param string $name
     * @param bool $showOnPage
     * @param string $listDescription
     * @return Language
     */
    public function createLanguageDb(
        string $name,
        bool $showOnPage,
        string $listDescription
    ): Language {
        return $this->languageRepository->persistAndFlush(
            $this->createLanguage($name, $showOnPage, $listDescription)
        );
    }
    /**
     * @return LanguageRepository
     */
    public function getRepository(): LanguageRepository
    {
        return $this->languageRepository;
    }
}