<?php

namespace TestLibrary\DataProvider;

use AdminBundle\Entity\Language;
use AdminBundle\Entity\LanguageInfo;
use AdminBundle\Entity\LanguageInfoText;
use Faker\Generator;
use LearningMetadata\Repository\Implementation\LanguageInfoRepository;
use Tests\TestLibrary\DataProvider\DefaultDataProviderInterface;

class LanguageInfoDataProvider implements DefaultDataProviderInterface
{
    /**
     * @var LanguageInfoRepository $languageInfoRepository
     */
    private $languageInfoRepository;
    /**
     * LanguageInfoDataProvider constructor.
     * @param LanguageInfoRepository $languageInfoRepository
     */
    public function __construct(
        LanguageInfoRepository $languageInfoRepository
    ) {
        $this->languageInfoRepository = $languageInfoRepository;
    }
    /**
     * @param Generator $faker
     * @param Language $language
     * @param int $textNumber
     * @return LanguageInfo
     */
    public function createDefault(Generator $faker, Language $language = null, int $textNumber = 5): LanguageInfo
    {
        if (!$language instanceof Language) {
            $message = sprintf('You have to provide a %s', Language::class);
            throw new \RuntimeException($message);
        }

        return $this->createLanguageInfo($faker, $language, $textNumber);
    }
    /**
     * @param Generator $faker
     * @param Language $language
     * @param int $textNumber
     * @return LanguageInfo
     */
    public function createDefaultDb(Generator $faker, Language $language = null, int $textNumber = null): LanguageInfo
    {
        return $this->languageInfoRepository->persistAndFlush($this->createDefault(
            $faker,
            $language,
            $textNumber
        ));
    }
    /**
     * @return LanguageInfoRepository
     */
    public function getLanguageInfoRepository(): LanguageInfoRepository
    {
        return $this->languageInfoRepository;
    }
    /**
     * @param Generator $faker
     * @param Language $language
     * @param int $textNumber
     * @return LanguageInfo
     */
    private function createLanguageInfo(Generator $faker, Language $language, int $textNumber): LanguageInfo
    {
        $languageInfo = new LanguageInfo();
        $languageInfo->setName($faker->name);
        $languageInfo->setLanguage($language);
        $languageInfo->setLanguageInfoTexts($this->createLanguageInfoTexts(
            $faker,
            $languageInfo,
            $textNumber
        ));

        return $languageInfo;
    }
    /**
     * @param Generator $faker
     * @param LanguageInfo $languageInfo
     * @param int $textNumber
     * @return array
     */
    private function createLanguageInfoTexts(
        Generator $faker,
        LanguageInfo $languageInfo,
        int $textNumber
    ): array {
        $texts = [];
        for ($i = 0; $i < $textNumber; $i++) {
            $languageInfoText = new LanguageInfoText();
            $languageInfoText->setName($faker->name);
            $languageInfoText->setText($faker->sentence(20));
            $languageInfoText->setLanguageInfo($languageInfo);

            $texts[] = $languageInfoText;
        }

        return $texts;
    }
}