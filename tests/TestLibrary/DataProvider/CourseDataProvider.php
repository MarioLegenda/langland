<?php

namespace Tests\TestLibrary\DataProvider;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Language;
use Faker\Generator;
use LearningMetadata\Repository\Implementation\CourseRepository;
use PublicApi\Infrastructure\Type\CourseType;

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
     * @return Course
     */
    public function createDefault(Generator $faker, Language $language = null): Course
    {
        if (!$language instanceof Language) {
            $language = $this->languageDataProvider->createDefaultDb($faker);
        }

        return $this->createCourse(
            $language,
            $faker->name,
            $faker->sentence(30)
        );
    }
    /**
     * @param Generator $faker
     * @param Language|null $language
     * @return Course
     */
    public function createDefaultDb(Generator $faker, Language $language = null): Course
    {
        return $this->courseRepository->persistAndFlush($this->createDefault($faker, $language));
    }
    /**
     * @param Language $language
     * @param string $name
     * @param string $whatToLearn
     * @param string|null $courseUrl
     * @return Course
     */
    public function createCourse(
        Language $language,
        string $name,
        string $whatToLearn,
        string $courseUrl = null
    ): Course {
        $course = new Course();
        $course->setName($name);
        $course->setLanguage($language);
        $course->setCourseOrder(1);
        $course->setType((string) CourseType::fromValue('Beginner'));
        $course->setCourseUrl((is_string($courseUrl)) ? $courseUrl : \URLify::filter($name));
        $course->setWhatToLearn($whatToLearn);

        return $course;
    }
    /**
     * @param Language $language
     * @param string $name
     * @param string $whatToLearn
     * @param string|null $courseUrl
     * @return Course
     */
    public function createCourseDb
    (
        Language $language,
        string $name,
        string $whatToLearn,
        string $courseUrl = null
    ): Course {
        return $this->courseRepository->persistAndFlush(
            $this->createCourse(
                $language,
                $name,
                $whatToLearn,
                $courseUrl
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
}