<?php

namespace Tests\TestLibrary\DataProvider;

use AdminBundle\Entity\Language;
use AdminBundle\Entity\Lesson;
use Faker\Generator;
use LearningMetadata\Repository\Implementation\LessonRepository;

class LessonDataProvider implements DefaultDataProviderInterface
{
    /**
     * @var LessonRepository $lessonRepository
     */
    private $lessonRepository;
    /**
     * @var LanguageDataProvider $languageDataProvider
     */
    private $languageDataProvider;
    /**
     * LessonDataProvider constructor.
     * @param LessonRepository $lessonRepository
     * @param LanguageDataProvider $languageDataProvider
     */
    public function __construct(
        LessonRepository $lessonRepository,
        LanguageDataProvider $languageDataProvider
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->languageDataProvider = $languageDataProvider;
     }
    /**
     * @param Language $language
     * @param Generator $faker
     * @param array|null $seedData
     * @return Lesson
     */
    public function createDefault(Generator $faker, Language $language = null, array $seedData = null): Lesson
    {
        if (!$language instanceof Language) {
            $language = $this->languageDataProvider->createDefaultDb($faker);
        }

        $seedData = $this->resolveSeedData($faker, $seedData);

        return $this->createLesson(
            $faker->name,
            $seedData['description'],
            $seedData['learningOrder'],
            $language,
            $seedData['type']
        );
    }
    /**
     * @param Generator $faker
     * @param Language|null $language
     * @param array|null $seedData
     * @return Lesson
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createDefaultDb(Generator $faker, Language $language = null, array $seedData = null): Lesson
    {
        $lesson = $this->lessonRepository->persistAndFlush(
            $this->createDefault($faker, $language, $seedData)
        );

        return $lesson;
    }
    /**
     * @param string $name
     * @param string $description
     * @param int $learningOrder
     * @param Language $language
     * @param string $type
     * @return Lesson
     */
    public function createLesson(
        string $name,
        string $description,
        int $learningOrder,
        Language $language,
        string $type
    ) {
        return new Lesson(
            $name,
            $type,
            $learningOrder,
            $description,
            $language
        );
    }
    /**
     * @param string $name
     * @param int $order
     * @param string $description
     * @param Language $language
     * @param string $type
     * @return Lesson
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createLessonDb(
        string $name,
        int $order,
        string $description,
        Language $language,
        string $type
    ) {
        return $this->lessonRepository->persistAndFlush(
            $this->createLesson(
                $name,
                $description,
                $order,
                $language,
                $type
            )
        );
    }
    /**
     * @return LessonRepository
     */
    public function getRepository(): LessonRepository
    {
        return $this->lessonRepository;
    }
    /**
     * @param Generator $faker
     * @param array|null $seedData
     * @return array
     */
    private function resolveSeedData(Generator $faker, array $seedData = null): array
    {
        $seedData = (is_null($seedData)) ? [] : $seedData;

        $properties = ['name', 'learningOrder', 'type', 'description'];

        $newSeedData = [];
        foreach ($properties as $property) {
            if (!array_key_exists($property, $seedData)) {
                if ($property === 'learningOrder') {
                    $newSeedData[$property] = rand(1, 10);

                    continue;
                }

                if ($properties === 'type') {
                    $newSeedData[$property] = 'Beginner';
                }

                $newSeedData[$property] = $faker->name;
            } else {
                $newSeedData[$property] = $seedData[$property];
            }
        }

        return $newSeedData;
    }
}