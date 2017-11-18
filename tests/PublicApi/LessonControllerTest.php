<?php

namespace Tests\PublicApi;

use Faker\Factory;
use Faker\Generator;
use TestLibrary\LanglandAdminTestCase;
use Tests\TestLibrary\DataProvider\CourseDataProvider;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;
use Tests\TestLibrary\DataProvider\LessonDataProvider;

class LessonControllerTest extends LanglandAdminTestCase
{
    /**
     * @var string $mainLessonRoute
     */
    private $mainLessonRoute = 'http://33.33.33.10/langland/api/v1/lesson/';
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
    }

    public function test_get_lesson_by_id()
    {
        $course = $this->courseDataProvider->createDefault($this->faker);
        $lesson = $this->lessonDataProvider->createDefault($this->faker, $course);
        $course->addLesson($lesson);

        $this->courseDataProvider->getRepository()->persistAndFlush($course);

        $route = sprintf($this->mainLessonRoute.'%d', $lesson->getId());

        $this->client->request('GET', $route);

        static::assertEquals(200, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);

        static::assertNotEmpty($content);

        static::assertArrayHasKey('id', $content);
        static::assertArrayHasKey('uuid', $content);
        static::assertArrayHasKey('lesson', $content);
        static::assertArrayHasKey('order', $content);
    }
}