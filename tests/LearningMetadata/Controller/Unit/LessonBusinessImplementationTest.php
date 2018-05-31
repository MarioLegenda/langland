<?php

namespace Tests\LearningMetadata\Controller\Unit;

use LearningMetadata\Business\Controller\LessonController;
use LearningMetadata\Infrastructure\Type\CourseType;
use Library\Infrastructure\Helper\Deserializer;
use LearningMetadata\Business\ViewModel\Lesson\LessonView;
use Library\Infrastructure\Helper\ModelValidator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestLibrary\ContainerAwareTest;
use LearningMetadata\Business\Middleware\LessonMiddleware;
use AdminBundle\Entity\Lesson;
use Tests\TestLibrary\DataProvider\LessonDataProvider;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;

class LessonBusinessImplementationTest extends ContainerAwareTest
{
    /**
     * @var LessonController $lessonController
     */
    private $lessonController;
    /**
     * @var Deserializer $deserializer
     */
    private $deserializer;
    /**
     * @var LessonDataProvider $lessonDataProvider
     */
    private $lessonDataProvider;
    /**
     * @var LanguageDataProvider $languageDataProvider
     */
    private $languageDataProvider;
    /**
     * @var ModelValidator $modelValidator
     */
    private $modelValidator;
    /**
     * @var LessonMiddleware $lessonMiddleware
     */
    private $lessonMiddleware;
    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->lessonController = $this->container->get('learning_metadata.controller.lesson');
        $this->deserializer = $this->container->get('library.deserializer');
        $this->lessonDataProvider = $this->container->get('data_provider.lesson');
        $this->languageDataProvider = $this->container->get('data_provider.language');
        $this->modelValidator = $this->container->get('library.model_validator');
        $this->lessonMiddleware = $this->container->get('learning_metadata.infrastructure.lesson_middleware');
    }

    public function test_lesson_creation()
    {
        $name = 'name-'.Uuid::uuid4()->toString();

        $language = $this->languageDataProvider->createDefaultDb($this->faker);

        $data = [
            'lesson' => [
                'type' => (string) CourseType::fromValue('Beginner'),
                'name' => $name,
                'learningOrder' => rand(0, 999),
                'description' => $this->faker->sentence(20),
            ]
        ];

        /** @var LessonView $lessonView */
        $lessonView = $this->deserializer->create($data['lesson'], LessonView::class);

        $lessonView->setLanguage($language);

        $this->lessonController->newAction($lessonView);

        /** @var Lesson $lesson */
        $lesson = $this->lessonDataProvider->getRepository()->findOneBy([
            'name' => $name,
        ]);

        static::assertInstanceOf(Lesson::class, $lesson);
        static::assertEquals($data['lesson']['name'], $name);
    }

    public function test_new_lesson()
    {
        $language = $this->languageDataProvider->createDefaultDb($this->faker);

        $data = [
            'lesson' => [
                'type' => (string) CourseType::fromValue('Beginner'),
                'language' => $language,
                'name' => 'name-'.Uuid::uuid4()->toString(),
                'learningOrder' => rand(0, 999),
                'description' => $this->faker->sentence(20),
            ],
        ];

        /** @var LessonView $lessonView */
        $lessonView = $this->deserializer->create($data['lesson'], LessonView::class);

        $lessonView->setLanguage($language);

        $response = $this->lessonController->newAction($lessonView);

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(201, $response->getStatusCode());
    }
    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function test_new_lesson_fail()
    {
        $name = 'name-'.Uuid::uuid4()->toString();

        $language = $this->languageDataProvider->createDefaultDb($this->faker);

        $data = [
            'lesson' => [
                'type' => (string) CourseType::fromValue('Beginner'),
                'language' => $language,
                'name' => $name,
                'learningOrder' => rand(0, 999),
                'description' => $this->faker->sentence(20),
            ]
        ];

        /** @var LessonView $lessonView */
        $lessonView = $this->deserializer->create($data['lesson'], LessonView::class);

        $lessonView->setLanguage($language);

        $response = $this->lessonController->newAction($lessonView);

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(201, $response->getStatusCode());

        $data = [
            'lesson' => [
                'type' => (string) CourseType::fromValue('Beginner'),
                'name' => $name,
                'learningOrder' => rand(0, 999),
                'description' => $this->faker->sentence(20),
            ],
        ];

        /** @var LessonView $lessonView */
        $lessonView = $this->deserializer->create($data['lesson'], LessonView::class);

        $response = $this->lessonController->newAction($lessonView);

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(400, $response->getStatusCode());
    }

    public function test_edit_lesson()
    {
        $name = 'name-'.Uuid::uuid4()->toString();

        $language = $this->languageDataProvider->createDefaultDb($this->faker);

        $initialData = [
            'lesson' => [
                'type' => (string) CourseType::fromValue('Beginner'),
                'name' => $name,
                'learningOrder' => rand(0, 999),
                'description' => $this->faker->sentence(20),
            ],
        ];

        /** @var LessonView $lessonView */
        $lessonView = $this->deserializer->create($initialData['lesson'], LessonView::class);

        $lessonView->setLanguage($language);

        $response = $this->lessonController->newAction($lessonView);

        /** @var Lesson $lesson */
        $lesson = $this->lessonDataProvider->getRepository()->findOneBy([
            'name' => $name,
        ]);

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(201, $response->getStatusCode());

        $initialData['id'] = $lesson->getId();
        $initialData['name'] = 'name-'.Uuid::uuid4()->toString();

        $lessonView = $this->deserializer->create($initialData['lesson'], LessonView::class);

        $lessonView->setLanguage($language);

        $response = $this->lessonController->updateAction($lessonView);

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(201, $response->getStatusCode());
    }
}