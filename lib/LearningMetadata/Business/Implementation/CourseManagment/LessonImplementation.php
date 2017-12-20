<?php

namespace LearningMetadata\Business\Implementation\CourseManagment;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Lesson;
use Library\Infrastructure\Helper\Deserializer;
use LearningMetadata\Business\ViewModel\Lesson\LessonView;
use Library\Presentation\Template\TemplateWrapper;
use Library\Infrastructure\Form\FormBuilderInterface;
use LearningMetadata\Repository\Implementation\CourseManagment\LessonRepository;
use LearningMetadata\Repository\Implementation\CourseRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpFoundation\Session\Session;

class LessonImplementation
{
    /**
     * @var TemplateWrapper $templateWrapper
     */
    private $templateWrapper;
    /**
     * @var LessonRepository $lessonRepository
     */
    private $lessonRepository;
    /**
     * @var CourseRepository $courseRepository
     */
    private $courseRepository;
    /**
     * @var FormBuilderInterface $formBuilder
     */
    private $formBuilder;
    /**
     * @var Router $router
     */
    private $router;
    /**
     * @var TraceableEventDispatcher $eventDispatcher
     */
    private $eventDispatcher;
    /**
     * @var Session $session
     */
    private $session;
    /**
     * @var Deserializer $deserializer
     */
    private $deserializer;
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * CategoryImplementation constructor.
     * @param LessonRepository $lessonRepository
     * @param CourseRepository $courseRepository
     * @param FormBuilderInterface $formBuilder
     * @param TemplateWrapper $templateWrapper
     * @param Router $router
     * @param Session $session
     * @param Deserializer $deserializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        LessonRepository $lessonRepository,
        CourseRepository $courseRepository,
        FormBuilderInterface $formBuilder,
        TemplateWrapper $templateWrapper,
        Router $router,
        TraceableEventDispatcher $eventDispatcher,
        Session $session,
        Deserializer $deserializer,
        LoggerInterface $logger
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->formBuilder = $formBuilder;
        $this->templateWrapper = $templateWrapper;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
        $this->deserializer = $deserializer;
        $this->logger = $logger;
        $this->courseRepository = $courseRepository;
    }
    /**
     * @param int $id
     * @return null|object
     */
    public function find(int $id)
    {
        $lesson = $this->lessonRepository->find($id);

        if (!$lesson instanceof Lesson) {
            throw new \RuntimeException(sprintf('Lesson with id %d not found', $id));
        }

        return $lesson;
    }
    /**
     * @param int $id
     * @return Lesson|null
     */
    public function tryFind(int $id): ?Lesson
    {
        /** @var Lesson $lesson */
        $lesson = $this->lessonRepository->find($id);

        return $lesson;
    }
    /**
     * @param string $name
     * @return Lesson|null
     */
    public function tryFindByName(string $name): ?Lesson
    {
        /** @var Lesson $lesson */
        $lesson = $this->lessonRepository->findOneBy([
            'name' => $name,
        ]);

        return $lesson;
    }
    /**
     * @return Lesson[]
     */
    public function getLessons() : array
    {
        return $this->lessonRepository->findAll();
    }
    /**
     * @param Course $course
     * @return Response
     */
    public function getListPresentation(Course $course) : Response
    {
        $template = '::Admin/Template/Panel/CourseManager/_listing.html.twig';
        $data = [
            'listing_title' => 'Lessons',
            'course' => $course,
            'lessons' => $this->getLessons(),
            'template' => '/Lesson/index.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }
    /**
     * @param Course $course
     * @return Response
     */
    public function createLesson(Course $course): Response
    {
        $template = '::Admin/Template/Panel/CourseManager/_action.html.twig';
        $data = [
            'listing_title' => 'Create lesson',
            'course' => $course,
            'template' => '/Lesson/create.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }
    /**
     * @param Course $course
     * @param Lesson $lesson
     * @return Response
     */
    public function editLessonView(Course $course, Lesson $lesson): Response
    {
        $template = '::Admin/Template/Panel/CourseManager/_action.html.twig';
        $data = [
            'listing_title' => sprintf('Edit lesson | %s', $lesson->getJsonLesson()['name']),
            'course' => $course,
            'lesson' => $lesson,
            'template' => '/Lesson/edit.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }
    /**
     * @param Course $course
     * @param LessonView $lessonView
     * @return JsonResponse
     */
    public function newLesson(Course $course, LessonView $lessonView)
    {
        $lesson = new Lesson(
            $lessonView->getName(),
            $lessonView->getUuid(),
            0,
            $lessonView->toArray(),
            $course
        );

        $course->addLesson($lesson);

        $lesson->setName($lessonView->getName());

        $this->courseRepository->persistAndFlush($course);

        return new JsonResponse(null, 201);
    }
    /**
     * @param LessonView $lessonView
     * @param Lesson $lesson
     * @return JsonResponse
     */
    public function updateLesson(LessonView $lessonView, Lesson $lesson)
    {
        $lesson->setJsonLesson($lessonView->toArray());

        $this->lessonRepository->persistAndFlush($lesson);

        return new JsonResponse(null, 201);
    }
    /**
     * @param int $statusCode
     * @param array $errors
     * @return JsonResponse
     */
    public function createErrorResponse(int $statusCode, array $errors): JsonResponse
    {
        return new JsonResponse($errors, $statusCode);
    }
}