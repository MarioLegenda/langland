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
use AdminBundle\Command\Helper\LanguageFactory;
use AdminBundle\Command\Helper\CourseFactory;
use AdminBundle\Entity\Language;
use Library\LearningMetadata\Business\Middleware\LessonMiddleware;
use AdminBundle\Entity\Lesson;

class LessonBusinessImplementationTest extends ContainerAwareTest
{
    /**
     * @var LessonRepository $lessonRepository
     */
    private $lessonRepository;
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
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->lessonRepository = $this->container->get('langland.learning_metadata.repository.implementation.lesson_implementation');
        $this->lessonImplementation = $this->container->get('langland.learning_metadata.business.implementation.lesson_implementation');
        $this->courseImplementation = $this->container->get('langland.learning_metadata.business.implementation.course_implementation');
        $this->deserializer = $this->container->get('library.deserializer');

        $em = $this->container->get('doctrine')->getManager();
        $languageFactory = new LanguageFactory($em);

        $languages = $languageFactory->create([
            'French',
            'English',
        ], true);

        $courseFactory = new CourseFactory($em);
        /** @var Language $language */
        foreach ($languages as $language) {
            $courseFactory->create($language, 5);
        }
    }

    public function test_lesson_creation()
    {
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

        $course = $this->courseImplementation->findCourse(1);

        $this->lessonImplementation->newLesson($course, $lessonView);

        /** @var Lesson $lesson */
        $lesson = $this->lessonRepository->findOneBy([
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
        $data = [
            'course' => 1,
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

        $response = $this->lessonImplementation->newLesson(
            $data['course'],
            $data['lessonView']
        );

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(201, $response->getStatusCode());
    }
}