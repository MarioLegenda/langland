<?php

namespace Library\LearningMetadata\Business\Implementation\CourseManagment;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Lesson;
use Library\Infrastructure\Helper\Deserializer;
use Library\LearningMetadata\Business\ViewModel\Lesson\LessonView;
use Library\LearningMetadata\Presentation\Template\TemplateWrapper;
use Library\Infrastructure\Form\FormBuilderInterface;
use Library\LearningMetadata\Repository\Implementation\CourseManagment\LessonRepository;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
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
     * @param FormBuilderInterface $formBuilder
     * @param TemplateWrapper $templateWrapper
     * @param Router $router
     * @param Session $session
     * @param Deserializer $deserializer
     * @param LoggerInterface $logger
     */
    public function __construct(
        LessonRepository $lessonRepository,
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
     * @param LessonView $lessonView
     * @return JsonResponse
     */
    public function newLesson(Course $course, LessonView $lessonView)
    {
        $lesson = new Lesson(
            $lessonView->getUuid(),
            0,
            $lessonView->toArray(),
            $course
        );

        $this->lessonRepository->persistAndFlush($lesson);

        return new JsonResponse(null, 201);
    }
}