<?php

namespace LearningMetadata\Business\Implementation;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Lesson;
use LearningMetadata\Business\ViewModel\Lesson\LessonView;
use Library\Infrastructure\Helper\SerializerWrapper;
use Library\Presentation\Template\TemplateWrapper;
use Library\Infrastructure\Form\FormBuilderInterface;
use LearningMetadata\Repository\Implementation\LessonRepository;
use LearningMetadata\Repository\Implementation\CourseRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
     * @var SerializerWrapper $serializerWrapper
     */
    private $serializerWrapper;
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
     * @param SerializerWrapper $serializerWrapper
     * @param LoggerInterface $logger
     */
    public function __construct(
        LessonRepository $lessonRepository,
        FormBuilderInterface $formBuilder,
        TemplateWrapper $templateWrapper,
        Router $router,
        TraceableEventDispatcher $eventDispatcher,
        Session $session,
        SerializerWrapper $serializerWrapper,
        LoggerInterface $logger
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->formBuilder = $formBuilder;
        $this->templateWrapper = $templateWrapper;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
        $this->serializerWrapper = $serializerWrapper;
        $this->logger = $logger;
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

        if (!$lesson instanceof Lesson) {
            return null;
        }

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
     * @return Response
     */
    public function getListPresentation() : Response
    {
        $template = '::Admin/Template/Panel/_listing.html.twig';
        $data = [
            'listing_title' => 'Lessons',
            'lessons' => $this->getLessons(),
            'template' => '/Lesson/index.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }
    /**
     * @return Response
     */
    public function createLesson(): Response
    {
        $template = '::Admin/Template/Panel/_action.html.twig';
        $data = [
            'listing_title' => 'Create lesson',
            'template' => '/Lesson/create.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }
    /**
     * @param Lesson $lesson
     * @return Response
     */
    public function editLessonView(Lesson $lesson): Response
    {
        $template = '::Admin/Template/Panel/_action.html.twig';
        $data = [
            'listing_title' => sprintf('Edit lesson | %s', $lesson->getJsonLesson()['name']),
            'lesson' => $lesson,
            'template' => '/Lesson/edit.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }
    /**
     * @param LessonView $lessonView
     * @return JsonResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function newLesson(LessonView $lessonView)
    {
        if ($this->tryFindByName($lessonView->getName())) {
            throw new BadRequestHttpException(sprintf('Lesson with name \'%s\' already exists', $lessonView->getName()));
        }

        $lesson = new Lesson(
            $lessonView->getName(),
            $lessonView->getType(),
            $lessonView->getLearningOrder(),
            $lessonView->getDescription(),
            $lessonView->getLanguage()
        );

        $lesson->setName($lessonView->getName());

        $this->lessonRepository->persistAndFlush($lesson);

        return new JsonResponse(null, 201);
    }
    /**
     * @param LessonView $lessonView
     * @return JsonResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateLesson(LessonView $lessonView)
    {
        $lesson = $this->tryFindByName($lessonView->getName());

        if (!$lesson instanceof Lesson) {
            throw new \RuntimeException(
                sprintf(
                    'Lesson with name \'%s\' not found',
                    $lessonView->getName()
                )
            );
        }

        $lesson->setType($lesson->getType());
        $lesson->setLearningOrder($lessonView->getLearningOrder());
        $lesson->setDescription($lessonView->getDescription());
        $lesson->setLanguage($lessonView->getLanguage());

        $this->lessonRepository->persistAndFlush($lesson);

        return new JsonResponse(null, 201);
    }
    /**
     * @return SerializerWrapper
     */
    public function getSerializerWrapper(): SerializerWrapper
    {
        return $this->serializerWrapper;
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