<?php

namespace Tests\PublicApi;

use AdminBundle\Entity\Lesson;
use Faker\Factory;
use Faker\Generator;
use PublicApi\Lesson\Business\Controller\LessonController;
use Ramsey\Uuid\Uuid;
use PublicApi\Lesson\Business\Implementation\LessonImplementation;
use Symfony\Component\HttpFoundation\Response;
use TestLibrary\LanglandAdminTestCase;
use Tests\TestLibrary\DataProvider\CourseDataProvider;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;
use Tests\TestLibrary\DataProvider\LessonDataProvider;

class LessonControllerTest extends LanglandAdminTestCase
{
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
     * @var LessonController $lessonController
     */
    private $lessonController;
    /**
     * @var LessonImplementation $lessonImplementation
     */
    private $lessonImplementation;
    /**
     * @var Generator $faker
     */
    private $faker;
    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->faker = Factory::create();

        $this->lessonDataProvider = $this->container->get('langland.data_provider.lesson');
        $this->courseDataProvider = $this->container->get('langland.data_provider.course');
        $this->languageDataProvider = $this->container->get('langland.data_provider.language');
        $this->lessonController = $this->container->get('langland.public_api.controller.lesson');
        $this->lessonImplementation = $this->container->get('langland.public_api.business.implementation.lesson');
    }

    public function test_lesson_implementation()
    {
        $course = $this->courseDataProvider->createDefault($this->faker);
        $lesson = $this->lessonDataProvider->createDefault($this->faker, $course);
        $course->addLesson($lesson);

        $this->courseDataProvider->getRepository()->persistAndFlush($course);

        /** @var string $serialized */
        $serialized = $this->lessonImplementation->findAndSerialize($lesson, ['public_api'], 'json');

        static::assertInternalType('string', $serialized);

        $decoded = json_decode($serialized, true);

        $this->assertValidLessonResponse($lesson, $decoded);
    }

    public function test_get_lesson_by_id()
    {
        $course = $this->courseDataProvider->createDefault($this->faker);
        $lesson = $this->lessonDataProvider->createDefault($this->faker, $course);
        $course->addLesson($lesson);

        $this->courseDataProvider->getRepository()->persistAndFlush($course);

        /** @var Response $response */
        $response = $this->lessonController->getLessonById($lesson);

        static::assertInstanceOf(Response::class, $response);
        static::assertInternalType('string', $response->getContent());

        $decoded = json_decode($response->getContent(), true);

        $this->assertValidLessonResponse($lesson, $decoded);
    }
    /**
     * @param Lesson $lesson
     * @param array $response
     */
    private function assertValidLessonResponse(Lesson $lesson, array $response)
    {
        static::assertArrayHasKey('id', $response);
        static::assertInternalType('int', $response['id']);
        static::assertEquals($lesson->getId(), $response['id']);
        static::assertArrayHasKey('uuid', $response);
        static::assertInternalType('string', $response['uuid']);
        static::assertTrue(Uuid::isValid($response['uuid']));
        static::assertInternalType('array', $response['lesson']);
    }
}