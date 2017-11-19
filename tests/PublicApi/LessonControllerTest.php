<?php

namespace Tests\PublicApi;

use Faker\Factory;
use Faker\Generator;
use Library\Lesson\Business\Implementation\LessonImplementation;
use Ramsey\Uuid\Uuid;
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
        $this->lessonImplementation = $this->container->get('langland.public_api.business.implementation.lesson');
    }

    public function test_get_lesson_by_id()
    {
        $course = $this->courseDataProvider->createDefault($this->faker);
        $lesson = $this->lessonDataProvider->createDefault($this->faker, $course);
        $course->addLesson($lesson);

        $this->courseDataProvider->getRepository()->persistAndFlush($course);

        $serialized = $this->lessonImplementation->findAndSerialize(
            $lesson->getId(),
            ['public_api'],
            'json'
        );

        static::assertInternalType('string', $serialized);

        $decoded = json_decode($serialized, true);

        static::assertArrayHasKey('id', $decoded);
        static::assertInternalType('int', $decoded['id']);
        static::assertEquals($lesson->getId(), $decoded['id']);
        static::assertArrayHasKey('uuid', $decoded);
        static::assertInternalType('string', $decoded['uuid']);
        static::assertTrue(Uuid::isValid($decoded['uuid']));
        static::assertInternalType('array', $decoded['lesson']);
    }
}