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
     * @param array|null $seedData
     * @return Lesson
     */
    public function createDefault(Generator $faker, Course $course = null, array $seedData = null): Lesson
    {
        if (!$course instanceof Course) {
            throw new \RuntimeException(sprintf('LessonDataProvider::createDefault() has to receive an %s object', Course::class));
        }

        $seedData = $this->resolveSeedData($faker, $seedData);

        return $this->createLesson(
            $course,
            $seedData['uuid'],
            $seedData['learningOrder'],
            ['name' => $faker->name]
        );
    }
    /**
     * @param Generator $faker
     * @param Course|null $course
     * @param array|null $seedData
     * @return Lesson
     */
    public function createDefaultDb(Generator $faker, Course $course = null, array $seedData = null): Lesson
    {
        if (!$course instanceof Course) {
            $course = $this->courseDataProvider->createDefaultDb($faker);
        }

        $lesson = $this->lessonRepository->persistAndFlush(
            $this->createDefault($faker, $course, $seedData)
        );

        $course->addLesson($lesson);
        $lesson->setCourse($course);

        return $lesson;
    }
    /**
     * @param Course $course
     * @param UuidInterface $lessonUuid
     * @param int $order
     * @param array $arrayLesson
     * @return Lesson
     */
    public function createLesson(
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
    /**
     * @param Generator $faker
     * @param array|null $seedData
     * @return array
     */
    private function resolveSeedData(Generator $faker, array $seedData = null): array
    {
        $seedData = (is_null($seedData)) ? [] : $seedData;

        $properties = ['name', 'uuid', 'learningOrder', 'jsonLesson'];

        $newSeedData = [];
        foreach ($properties as $property) {
            if (!array_key_exists($property, $seedData)) {
                if ($property === 'learningOrder') {
                    $newSeedData[$property] = rand(1, 10);

                    continue;
                }

                if ($property === 'uuid') {
                    $newSeedData[$property] = Uuid::uuid4();

                    continue;
                }

                if ($property === 'jsonLesson') {
                    $newSeedData[$property] = ['name' => $faker->name];
                }

                $newSeedData[$property] = $faker->name;
            } else {
                $newSeedData[$property] = $seedData[$property];
            }
        }

        return $newSeedData;
    }
}