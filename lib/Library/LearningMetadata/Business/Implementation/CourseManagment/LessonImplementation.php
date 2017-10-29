<?php

namespace Library\LearningMetadata\Business\Implementation\CourseManagment;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Lesson;
use Library\LearningMetadata\Business\ViewModel\Lesson\LessonView;
use Library\LearningMetadata\Presentation\Template\TemplateWrapper;
use Library\Infrastructure\Form\FormBuilderInterface;
use Library\LearningMetadata\Repository\Implementation\CourseManagment\LessonRepository;
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
     * CategoryImplementation constructor.
     * @param LessonRepository $lessonRepository
     * @param FormBuilderInterface $formBuilder
     * @param TemplateWrapper $templateWrapper
     * @param Router $router
     * @param Session $session
     */
    public function __construct(
        LessonRepository $lessonRepository,
        FormBuilderInterface $formBuilder,
        TemplateWrapper $templateWrapper,
        Router $router,
        TraceableEventDispatcher $eventDispatcher,
        Session $session
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->formBuilder = $formBuilder;
        $this->templateWrapper = $templateWrapper;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
    }
    /**
     * @return Lesson[]
     */
    public function getLessons() : array
    {
        $lessons = $this->lessonRepository->findAll();

        $lessonViews = [];
        /** @var Lesson $lesson */
        foreach ($lessons as $lesson) {
            $lessonViews[] = LessonView::createFromArray($lesson->getJsonLesson());
        }

        return $lessonViews;
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
}