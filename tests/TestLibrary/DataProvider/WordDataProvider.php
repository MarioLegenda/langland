<?php

namespace TestLibrary\DataProvider;

use AdminBundle\Command\Helper\FakerTrait;
use AdminBundle\Entity\Language;
use AdminBundle\Entity\Lesson;
use AdminBundle\Entity\Translation;
use AdminBundle\Entity\Word;
use Faker\Generator;
use LearningMetadata\Repository\Implementation\WordRepository;
use Tests\TestLibrary\DataProvider\DefaultDataProviderInterface;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;
use Tests\TestLibrary\DataProvider\LessonDataProvider;

class WordDataProvider implements DefaultDataProviderInterface
{
    /**
     * @var WordRepository $wordRepository
     */
    private $wordRepository;
    /**
     * @var LanguageDataProvider $languageDataProvider
     */
    private $languageDataProvider;
    /**
     * @var LessonDataProvider $lessonDataProvider
     */
    private $lessonDataProvider;
    /**
     * UserDataProvider constructor.
     * @param WordRepository $wordRepository
     * @param LanguageDataProvider $languageDataProvider
     * @param LessonDataProvider $lessonDataProvider;
     */
    public function __construct(
        WordRepository $wordRepository,
        LanguageDataProvider $languageDataProvider,
        LessonDataProvider $lessonDataProvider
    ) {
        $this->wordRepository = $wordRepository;
        $this->lessonDataProvider = $lessonDataProvider;
        $this->languageDataProvider = $languageDataProvider;
    }
    /**
     * @param Generator $faker
     * @param Language|null $language
     * @param array|null $seedData
     * @return Word
     */
    public function createDefault(Generator $faker, Language $language = null, array $seedData = null): Word
    {
        if (!$language instanceof Language) {
            $language = $this->languageDataProvider->createDefaultDb($faker);
        }

        $seedData = $this->resolveSeedData($faker, $seedData);

        $word = new Word();
        $word->setLanguage($language);
        $word->setName($seedData['name']);
        $word->setType($seedData['type']);
        $word->setLevel($seedData['level']);
        $word->setDescription($seedData['description']);
        $word->setPluralForm($seedData['plural_form']);

        $this->createTranslations($faker, $word);

        return $word;
    }
    /**
     * @param Generator $faker
     * @param Language|null $language
     * @param array|null $seedData
     * @return Word
     */
    public function createDefaultDb(
        Generator $faker,
        Language $language = null,
        array $seedData = null
    ): Word {

        return $this->wordRepository->persistAndFlush(
            $this->createDefault(
                $faker,
                $language,
                $seedData
            )
        );
    }
    /**
     * @param Generator $faker
     * @param Language|null $language
     * @param Lesson|null $lesson
     * @param array|null $seedData
     * @return Word
     */
    public function createWithLesson(
        Generator $faker,
        Language $language = null,
        Lesson $lesson = null,
        array $seedData = null
    ): Word {
        if (!$language instanceof Language) {
            $language = $this->languageDataProvider->createDefaultDb($faker);
        }

        if (!$lesson instanceof Lesson) {
            $lesson = $this->lessonDataProvider->createDefaultDb($faker);
        }

        $word = $this->createDefault($faker, $language, $seedData);
        $word->setLesson($lesson);

        $this->createTranslations($faker, $word);

        $this->wordRepository->persistAndFlush($word);

        return $word;
    }
    /**
     * @return WordRepository
     */
    public function getRepository(): WordRepository
    {
        return $this->wordRepository;
    }
    /**
     * @param Generator $faker
     * @param Word $word
     */
    private function createTranslations(Generator $faker, Word $word)
    {
        for ($i = 0; $i < 10; $i++) {
            $translation = $this->createTranslation($faker);

            $translation->setWord($word);

            $word->addTranslation($translation);
        }
    }
    /**
     * @param Generator $faker
     * @return Translation
     */
    private function createTranslation(Generator $faker): Translation
    {
        $translation = new Translation();
        $translation->setName($faker->name);

        return $translation;
    }
    /**
     * @param Generator $faker
     * @param array|null $seedData
     * @return array
     */
    private function resolveSeedData(Generator $faker, array $seedData = null): array
    {
        $seedData = (is_null($seedData)) ? [] : $seedData;

        $properties = ['name', 'type', 'level', 'description', 'plural_form'];

        $newSeedData = [];
        foreach ($properties as $property) {
            if (!array_key_exists($property, $seedData)) {
                if ($property === 'level') {
                    $newSeedData[$property] = rand(1, 10);

                    continue;
                }

                $newSeedData[$property] = $faker->name;
            } else {
                $newSeedData[$property] = $seedData[$property];
            }
        }

        return $newSeedData;
    }
}