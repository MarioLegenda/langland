<?php

namespace Tests\LearningMetadata\Controller\Unit;

use AdminBundle\Entity\Course;
use Library\Infrastructure\Helper\Deserializer;
use Library\LearningMetadata\Business\Implementation\CourseImplementation;
use Library\LearningMetadata\Business\Implementation\CourseManagment\LessonImplementation;
use Library\LearningMetadata\Business\ViewModel\Lesson\LessonView;
use Library\LearningMetadata\Repository\Implementation\CourseManagment\LessonRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestLibrary\ContainerAwareTest;
use Library\LearningMetadata\Business\Middleware\LessonMiddleware;
use AdminBundle\Entity\Lesson;
use Tests\TestLibrary\DataProvider\LessonDataProvider;
use Tests\TestLibrary\DataProvider\CourseDataProvider;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;

class LessonBusinessImplementationTest extends ContainerAwareTest
{
    /**
     * @var LessonImplementation $lessonImplementation
     */
    private $lessonImplementation;
    /**
     * @var CourseImplementation $courseImplementation
     */
    private $courseImplementation;
    /**
     * @var Deserializer $deserializer
     */
    private $deserializer;
    /**
     * @var LessonDataProvider $lessonDataProvider
     */
    private $lessonDataProvider;
    /**
     * @var CourseDataProvider $courseDataProvider
     */
    private $courseDataProvider;
    /**
     * @var LanguageDataProvider $languageDataProvider
     */
    private $languageDataProvider;
    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->lessonImplementation = $this->container->get('langland.learning_metadata.business.implementation.lesson');
        $this->courseImplementation = $this->container->get('langland.learning_metadata.business.implementation.course');
        $this->deserializer = $this->container->get('library.deserializer');
        $this->lessonDataProvider = $this->container->get('langland.data_provider.lesson');
        $this->courseDataProvider = $this->container->get('langland.data_provider.course');
        $this->languageDataProvider = $this->container->get('langland.data_provider.language');
    }

    public function test_lesson_creation()
    {
        $course = $this->courseDataProvider->createDefault($this->faker);
        $lesson = $this->lessonDataProvider->createDefault($this->faker, $course);
        $course->addLesson($lesson);

        $this->courseDataProvider->getRepository()->persistAndFlush($course);

        $uuid = Uuid::uuid4();
        $data = [
            'tips' => [
                'Tip 1',
                'Tip 2',
                'Tip 3',
            ],
            'lessonTexts' => [
                'Lesson text 1',
                'Lesson text 2',
                'Lesson text 3',
            ],
            'name' => $this->faker->word,
        ];

        /** @var LessonView $lessonView */
        $lessonView = $this->deserializer->create($data, LessonView::class);
        $lessonView->setUuid($uuid);

        $course = $this->courseImplementation->findCourse($course->getId());

        $this->lessonImplementation->newLesson($course, $lessonView);

        /** @var Lesson $lesson */
        $lesson = $this->lessonDataProvider->getRepository()->findOneBy([
            'uuid' => $uuid->toString(),
        ]);

        static::assertInstanceOf(Lesson::class, $lesson);
        static::assertEquals($uuid->toString(), $lesson->getUuid()->toString());
        static::assertNotEmpty($lesson->getJsonLesson());

        static::assertJsonStringEqualsJsonString(
            json_encode($data, true),
            json_encode($lesson->getJsonLesson(), true)
        );
    }

    public function test_new_lesson()
    {
        /** @var Course $course */
        $course = $this->courseDataProvider->createDefault($this->faker);

        $this->courseDataProvider->getRepository()->persistAndFlush($course);

        $data = [
            'course' => $course->getId(),
            'tips' => [
                'Tip 1',
                'Tip 2',
                'Tip 3',
            ],
            'lessonTexts' => [
                'Lesson text 1',
                'Lesson text 2',
                'Lesson text 3',
            ],
            'name' => $this->faker->word,
        ];

        $lessonMiddleware = new LessonMiddleware();
        $data = $lessonMiddleware->createNewLessonMiddleware(
            $data,
            $this->courseImplementation,
            $this->deserializer
        );

        $data['lessonView']->setUuid(Uuid::uuid4());

        $response = $this->lessonImplementation->newLesson(
            $data['course'],
            $data['lessonView']
        );

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(201, $response->getStatusCode());

        $course = $this->courseImplementation->findCourse($course->getId());

        static::assertInstanceOf(Course::class, $course);
        static::assertFalse($course->getLessons()->isEmpty());

        $lesson = $course->getLessons()[0];

        static::assertInstanceOf(Lesson::class, $lesson);
    }

    public function test_lesson_update()
    {
        /** @var Course $course */
        $course = $this->courseDataProvider->createDefault($this->faker);

        $this->courseDataProvider->getRepository()->persistAndFlush($course);

        $data = [
            'course' => $course->getId(),
            'tips' => [
                'Tip 1',
                'Tip 2',
                'Tip 3',
            ],
            'lessonTexts' => [
                'Lesson text 1',
                'Lesson text 2',
                'Lesson text 3',
            ],
            'name' => $this->faker->word,
        ];

        $lessonMiddleware = new LessonMiddleware();
        $data = $lessonMiddleware->createNewLessonMiddleware(
            $data,
            $this->courseImplementation,
            $this->deserializer
        );

        static::assertArrayHasKey('lessonView', $data);

        /** @var LessonView $lessonView */
        $lessonView = $data['lessonView'];
        $lessonView->setUuid(Uuid::uuid4());

        $response = $this->lessonImplementation->newLesson(
            $data['course'],
            $data['lessonView']
        );

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(201, $response->getStatusCode());

        $course = $this->courseImplementation->findCourse($course->getId());

        static::assertInstanceOf(Course::class, $course);
        static::assertFalse($course->getLessons()->isEmpty());

        $lesson = $course->getLessons()[0];

        static::assertInstanceOf(Lesson::class, $lesson);

        /** @var Lesson $existingLesson */
        $existingLesson = $this->lessonImplementation->find($lesson->getId());

        $data = $existingLesson->getJsonLesson();

        $data['id'] = $lesson->getId();
        $data['tips'] = [];
        $data['tips'][] = 'Tip 1';

        $lessonData = $lessonMiddleware->createExistingLessonMiddleware(
            $data,
            $this->lessonImplementation,
            $this->deserializer
        );

        static::assertArrayHasKey('lesson', $lessonData);
        static::assertArrayHasKey('lessonView', $lessonData);

        static::assertInstanceOf(LessonView::class, $lessonData['lessonView']);

        /** @var LessonView $lessonView */
        $updatedLessonView = $lessonData['lessonView'];

        $response = $this->lessonImplementation->updateLesson($updatedLessonView, $existingLesson);

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(205, $response->getStatusCode());

        $existingLesson = $this->lessonImplementation->find($existingLesson->getId());

        $lessonData = $lessonMiddleware->createExistingLessonMiddleware(
            $data,
            $this->lessonImplementation,
            $this->deserializer
        );

        static::assertArrayHasKey('lesson', $lessonData);
        static::assertArrayHasKey('lessonView', $lessonData);

        static::assertInstanceOf(LessonView::class, $lessonData['lessonView']);

        /** @var LessonView $lessonView */
        $updatedLessonView = $lessonData['lessonView'];

        static::assertEquals($lessonView->getName(), $updatedLessonView->getName());
        static::assertEquals(1, count($updatedLessonView->getTips()));
    }
}