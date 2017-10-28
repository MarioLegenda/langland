<?php

namespace Library\LearningMetadata\Business\Implementation;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Language;
use Library\Infrastructure\Form\FormBuilderInterface;
use Library\LearningMetadata\Infrastructure\Form\Type\CourseType;
use Library\LearningMetadata\Presentation\Template\TemplateWrapper;
use Library\LearningMetadata\Repository\Implementation\CourseRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Router;
use Library\Event\EntityProcessorEvent;

class CourseImplementation
{
    /**
     * @var TemplateWrapper $templateWrapper
     */
    private $templateWrapper;
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
     * CourseImplementation constructor.
     * @param CourseRepository $courseRepository
     * @param FormBuilderInterface $formBuilder
     * @param TemplateWrapper $templateWrapper
     * @param Router $router
     * @param Session $session
     */
    public function __construct(
        CourseRepository $courseRepository,
        FormBuilderInterface $formBuilder,
        TemplateWrapper $templateWrapper,
        Router $router,
        TraceableEventDispatcher $eventDispatcher,
        Session $session
    ) {
        $this->courseRepository = $courseRepository;
        $this->formBuilder = $formBuilder;
        $this->templateWrapper = $templateWrapper;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
    }
    /**
     * @return Course[]
     */
    public function getCourses() : array
    {
        return $this->courseRepository->findAll();
    }
    /**
     * @param array|Language|null $data
     * @param array $options
     * @return FormInterface
     */
    public function createForm($data = null, array $options = array()) : FormInterface
    {
        return $this->formBuilder->getForm(CourseType::class, $data);
    }
    /**
     * @param string $template
     * @param array $data
     * @return Response
     */
    public function getListPresentation(string $template = null, array $data = null) : Response
    {
        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_listing.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Course',
            'courses' => $this->getCourses(),
            'template' => '/Course/index.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }
    /**
     * @param string|null $template
     * @param array|null $data
     * @return Response
     */
    public function getCreatePresentation(string $template = null, array $data = null) : Response
    {
        $form = $this->createForm(new Course());

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Courses',
            'form' => $form->createView(),
            'template' => '/Course/create.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }
    /**
     * @param Request $request
     * @param string|null $template
     * @param array|null $data
     * @return Response
     * @throws \Throwable
     */
    public function newCourse(Request $request, string $template = null, array $data = null) : Response
    {
        $course = new Course();
        $form = $this->createForm($course);
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST' and $form->isSubmitted() and $form->isValid()) {

            $this->dispatchEvent(EntityProcessorEvent::class, array(
                'course' => $course,
            ));

            $this->courseRepository->persistAndFlush($course);

            $this->session->getFlashBag()->add(
                'notice',
                sprintf('Course created successfully')
            );

            return new RedirectResponse($this->router->generate('admin_course_create'));
        }

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Courses',
            'form' => $form->createView(),
            'template' => '/Course/create.html.twig',
        ];

        return new Response(
            $this->templateWrapper->getTemplate($template, $data),
            400
        );
    }
    /**
     * @param Request $request
     * @param int $id
     * @param string|null $template
     * @param array|null $data
     * @return Response
     * @throws \Throwable
     */
    public function updateCourse(Request $request, int $id, string $template = null, array $data = null) : Response
    {
        $course = $this->findCourse($id);

        if (!$course instanceof Course) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm($course);
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST' and $form->isSubmitted() and $form->isValid()) {

            $this->dispatchEvent(EntityProcessorEvent::class, array(
                'course' => $course,
            ));

            $this->courseRepository->persistAndFlush($course);

            $this->session->getFlashBag()->add(
                'notice',
                sprintf('Course updated successfully')
            );

            return new RedirectResponse($this->router->generate('admin_course_update', [
                'id' => $id
            ]));
        }

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'course' => $course,
            'listing_title' => 'Courses',
            'form' => $form->createView(),
            'template' => '/Course/update.html.twig',
        ];

        if ($form->isSubmitted() and !$form->isValid()) {
            return new Response(
                $this->templateWrapper->getTemplate($template, $data),
                400
            );
        }

        return new Response(
            $this->templateWrapper->getTemplate($template, $data),
            200
        );
    }
    /**
     * @param Course $course
     * @return Response
     */
    public function manageCourse(Course $course)
    {
        return new Response($this->templateWrapper->getTemplate('::Admin/Template/Panel/CourseManager/Dashboard/dashboard.html.twig', [
            'course' => $course,
        ]));
    }
    /**
     * @param int $id
     * @return Course
     */
    public function findCourse(int $id) : ?Course
    {
        /** @var Course $course */
        $course = $this->courseRepository->find($id);

        return $course;
    }
    /**
     * @param string $eventClass
     * @param $entity
     * @return void
     */
    protected function dispatchEvent(string $eventClass, $entity)
    {
        $event = new $eventClass($entity);

        $this->eventDispatcher->dispatch($event::NAME, $event);
    }
}