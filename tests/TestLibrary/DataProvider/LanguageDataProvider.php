<?php

namespace Tests\TestLibrary\DataProvider;

use AdminBundle\Entity\Language;
use Faker\Generator;
use LearningMetadata\Repository\Implementation\LanguageRepository;

class LanguageDataProvider implements DefaultDataProviderInterface
{
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * @var ImageDataProvider $imageDataProvider
     */
    private $imageDataProvider;
    /**
     * LanguageDataProvider constructor.
     * @param LanguageRepository $languageRepository
     * @param ImageDataProvider $imageDataProvider
     */
    public function __construct(
        LanguageRepository $languageRepository,
        ImageDataProvider $imageDataProvider
    ) {
        $this->languageRepository = $languageRepository;
        $this->imageDataProvider = $imageDataProvider;
    }
    /**
     * @param Generator $faker
     * @return Language
     */
    public function createDefault(Generator $faker): Language
    {
        $images = [
            'icon' => $this->imageDataProvider->createDefault($faker)->toArray(),
            'cover_image' => $this->imageDataProvider->createDefault($faker)->toArray(),
        ];

        return $this->createLanguage(
            $faker->name,
            true,
            $faker->sentence(30),
            $images
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
     * @param array $images
     * @return Language
     */
    public function createLanguage(
        string $name,
        bool $showOnPage,
        string $listDescription,
        array $images
    ): Language {
        $language = new Language();
        $language->setName($name);
        $language->setShowOnPage($showOnPage);
        $language->setListDescription($listDescription);
        $language->setImages($images);

        return $language;
    }
    /**
     * @param string $name
     * @param bool $showOnPage
     * @param string $listDescription
     * @param array $images
     * @return Language
     */
    public function createLanguageDb(
        string $name,
        bool $showOnPage,
        string $listDescription,
        array $images
    ): Language {
        return $this->languageRepository->persistAndFlush(
            $this->createLanguage($name, $showOnPage, $listDescription, $images)
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