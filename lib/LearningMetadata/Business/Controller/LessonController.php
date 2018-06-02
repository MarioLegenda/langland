<?php

namespace LearningMetadata\Business\Controller;

use AdminBundle\Entity\Lesson;
use LearningMetadata\Business\ViewModel\Lesson\LessonView;
use Library\Infrastructure\Helper\Deserializer;
use LearningMetadata\Business\Implementation\CourseImplementation;
use LearningMetadata\Business\Implementation\LessonImplementation;
use LearningMetadata\Business\Middleware\LessonMiddleware;
use Library\Infrastructure\Helper\ModelValidator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class LessonController
{
    /**
     * @var LessonImplementation $lessonImplementation
     */
    private $lessonImplementation;
    /**
     * @var LessonMiddleware $lessonMiddleware
     */
    private $lessonMiddleware;
    /**
     * LessonController constructor.
     * @param LessonImplementation $lessonImplementation
     * @param LessonMiddleware $lessonMiddleware
     */
    public function __construct(
        LessonImplementation $lessonImplementation,
        LessonMiddleware $lessonMiddleware
    ) {
        $this->lessonImplementation = $lessonImplementation;
        $this->lessonMiddleware = $lessonMiddleware;
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_VIEW')")
     *
     * @return Response
     */
    public function indexViewAction(): Response
    {
        return $this->lessonImplementation->getListPresentation();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @return Response
     */
    public function createViewAction(): Response
    {
        return $this->lessonImplementation->createLesson();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param int $lessonId
     * @return Response
     */
    public function editViewAction(int $lessonId)
    {
        $lesson = $this->lessonImplementation->tryFind($lessonId);

        if (!$lesson instanceof Lesson) {
            throw new NotFoundHttpException();
        }

        return $this->lessonImplementation->editLessonView($lesson);
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param LessonView $lessonView
     * @return JsonResponse
     */
    public function newAction(LessonView $lessonView): JsonResponse
    {
        return $this->lessonImplementation->newLesson($lessonView);
    }
    /**
     * @param LessonView $lessonView
     * @return JsonResponse
     */
    public function updateAction(LessonView $lessonView): JsonResponse
    {
        return $this->lessonImplementation->updateLesson($lessonView);
    }
    /**
     * @param Lesson $lesson
     * @return Response
     */
    public function getLessonById(Lesson $lesson)
    {
        $serializedLesson = $this->lessonImplementation
            ->getSerializerWrapper()
            ->serialize($lesson, ['public_api']);

        return new Response($serializedLesson, 200);
    }
}