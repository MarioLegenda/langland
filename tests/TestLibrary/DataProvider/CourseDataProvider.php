<?php

namespace Tests\TestLibrary\DataProvider;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Language;
use Faker\Generator;
use LearningMetadata\Repository\Implementation\CourseRepository;
use PublicApi\Infrastructure\Type\CourseType;
use PublicApi\Infrastructure\Type\TypeInterface;

class CourseDataProvider implements DefaultDataProviderInterface
{
    /**
     * @var CourseRepository $courseRepository
     */
    private $courseRepository;
    /**
     * @var LanguageDataProvider $languageDataProvider
     */
    private $languageDataProvider;
    /**
     * CourseDataProvider constructor.
     * @param CourseRepository $courseRepository
     * @param LanguageDataProvider $languageDataProvider
     */
    public function __construct(
        CourseRepository $courseRepository,
        LanguageDataProvider $languageDataProvider
    ) {
        $this->courseRepository = $courseRepository;
        $this->languageDataProvider = $languageDataProvider;
    }
    /**
     * @param Generator $faker
     * @param Language|null $language
     * @param array|null $seedData
     * @return Course
     */
    public function createDefault(Generator $faker, Language $language = null, array $seedData = null): Course
    {
        if (!$language instanceof Language) {
            $language = $this->languageDataProvider->createDefaultDb($faker);
        }

        $seedData = $this->resolveSeedData($faker, $seedData);

        return $this->createCourse(
            $language,
            $seedData['name'],
            $seedData['whatToLearn'],
            $seedData['courseUrl'],
            $seedData['courseOrder'],
            $seedData['type']
        );
    }
    /**
     * @param Generator $faker
     * @param Language|null $language
     * @param array|null $seedData
     * @return Course
     */
    public function createDefaultDb(Generator $faker, Language $language = null, array $seedData = null): Course
    {
        return $this->courseRepository->persistAndFlush($this->createDefault($faker, $language, $seedData));
    }
    /**
     * @param Language $language
     * @param string $name
     * @param string $whatToLearn
     * @param string|null $courseUrl
     * @param int $courseOrder
     * @param CourseType $type
     * @return Course
     */
    public function createCourse(
        Language $language,
        string $name,
        string $whatToLearn,
        string $courseUrl = null,
        int $courseOrder = 0,
        CourseType $type
    ): Course {
        $course = new Course();
        $course->setName($name);
        $course->setLanguage($language);
        $course->setCourseOrder($courseOrder);
        $course->setType((string) $type);
        $course->setCourseUrl((is_string($courseUrl)) ? $courseUrl : \URLify::filter($name));
        $course->setWhatToLearn($whatToLearn);

        return $course;
    }
    /**
     * @param Language $language
     * @param string $name
     * @param string $whatToLearn
     * @param string|null $courseUrl
     * @param int $courseOrder
     * @param CourseType $type
     * @return Course
     */
    public function createCourseDb (
        Language $language,
        string $name,
        string $whatToLearn,
        string $courseUrl = null,
        int $courseOrder = 0,
        CourseType $type
    ): Course {
        return $this->courseRepository->persistAndFlush(
            $this->createCourse(
                $language,
                $name,
                $whatToLearn,
                $courseUrl,
                $courseOrder,
                $type
            )
        );
    }
    /**
     * @return CourseRepository
     */
    public function getRepository(): CourseRepository
    {
        return $this->courseRepository;
    }
    /**
     * @param Generator $faker
     * @param array|null $seedData
     * @return array
     */
    private function resolveSeedData(Generator $faker, array $seedData = null): array
    {
        $seedData = (is_null($seedData)) ? [] : $seedData;

        $properties = ['name', 'whatToLearn', 'courseUrl', 'courseOrder', 'type'];

        $newSeedData = [];
        foreach ($properties as $property) {
            if (!array_key_exists($property, $seedData)) {
                if ($property === 'courseOrder') {
                    $newSeedData[$property] = rand(1, 10);

                    continue;
                }

                if ($property === 'type') {
                    $newSeedData[$property] = CourseType::fromValue('Beginner');

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