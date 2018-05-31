<?php

namespace Tests\PublicApi;

use AdminBundle\Entity\Lesson;
use Faker\Factory;
use Faker\Generator;
use LearningMetadata\Business\Controller\LessonController;
use LearningMetadata\Business\Implementation\LessonImplementation;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use TestLibrary\LanglandAdminTestCase;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;
use Tests\TestLibrary\DataProvider\LessonDataProvider;

class LessonControllerTest extends LanglandAdminTestCase
{
    /**
     * @var LessonDataProvider $lessonDataProvider
     */
    private $lessonDataProvider;
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

        $this->lessonDataProvider = $this->container->get('data_provider.lesson');
        $this->languageDataProvider = $this->container->get('data_provider.language');
        $this->lessonController = $this->container->get('learning_metadata.controller.lesson');
        $this->lessonImplementation = $this->container->get('learning_metadata.business.implementation.lesson');
    }

    public function test_lesson_implementation()
    {
        /** @var string $serialized */
        $serialized = $this->lessonImplementation->findAndSerialize($lesson, ['public_api'], 'json');

        static::assertInternalType('string', $serialized);

        $decoded = json_decode($serialized, true);

        $this->assertValidLessonResponse($lesson, $decoded);
    }

    public function test_get_lesson_by_id()
    {
        $language = $this->languageDataProvider->createDefaultDb($this->faker);

        $lesson = $this->lessonDataProvider->createDefaultDb($language, $this->faker);

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