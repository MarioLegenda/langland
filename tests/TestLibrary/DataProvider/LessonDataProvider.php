<?php

namespace Tests\TestLibrary\DataProvider;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Lesson;
use Faker\Generator;
use LearningMetadata\Repository\Implementation\CourseManagment\LessonRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class LessonDataProvider implements DefaultDataProviderInterface
{
    /**
     * @var LessonRepository $lessonRepository
     */
    private $lessonRepository;
    /**
     * @var CourseDataProvider $courseDataProvider
     */
    private $courseDataProvider;
    /**
     * LessonDataProvider constructor.
     * @param LessonRepository $lessonRepository
     * @param CourseDataProvider $courseDataProvider
     */
    public function __construct(
        LessonRepository $lessonRepository,
        CourseDataProvider $courseDataProvider
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->courseDataProvider = $courseDataProvider;
    }
    /**
     * @param Generator $faker
     * @param Course $course
     * @return Lesson
     */
    public function createDefault(Generator $faker, Course $course = null): Lesson
    {
        if (!$course instanceof Course) {
            throw new \RuntimeException(sprintf('LessonDataProvider::createDefault() has to receive an %s object', Course::class));
        }

        $course = $this->courseDataProvider->createDefault($faker);

        return $this->createLesson(
            $course,
            Uuid::uuid4(),
            rand(0, 999),
            ['name' => $faker->name]
        );
    }
    /**
     * @param Generator $faker
     * @return Lesson
     */
    public function createDefaultDb(Generator $faker): Lesson
    {
        $course = $this->courseDataProvider->createDefaultDb($faker);

        $lesson = $this->lessonRepository->persistAndFlush(
            $this->createDefault($faker, $course)
        );

        $course->addLesson($lesson);

        $this->courseDataProvider->getRepository()->persistAndFlush($course);

        return $lesson;
    }
    /**
     * @param Course $course
     * @param UuidInterface $lessonUuid
     * @param int $order
     * @param array $arrayLesson
     * @return Lesson
     */
    public function createLesson
    (
        Course $course,
        UuidInterface $lessonUuid,
        int $order,
        array $arrayLesson
    ) {
        return new Lesson(
            $arrayLesson['name'],
            $lessonUuid,
            $order,
            $arrayLesson,
            $course
        );
    }
    /**
     * @param Course $course
     * @param UuidInterface $lessonUuid
     * @param int $order
     * @param array $arrayLesson
     * @return Lesson
     */
    public function createLessonDb
    (
        Course $course,
        UuidInterface $lessonUuid,
        int $order,
        array $arrayLesson
    ) {
        return $this->lessonRepository->persistAndFlush(
            $this->createLesson(
                $course,
                $lessonUuid,
                $order,
                $arrayLesson
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
}