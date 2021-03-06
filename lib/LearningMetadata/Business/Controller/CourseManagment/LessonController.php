<?php

namespace LearningMetadata\Business\Controller\CourseManagment;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Lesson;
use Library\Infrastructure\Helper\Deserializer;
use LearningMetadata\Business\Implementation\CourseImplementation;
use LearningMetadata\Business\Implementation\CourseManagment\LessonImplementation;
use LearningMetadata\Business\Middleware\LessonMiddleware;
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
     * @var CourseImplementation $courseImplementation
     */
    private $courseImplementation;
    /**
     * @var Deserializer $deserializer
     */
    private $deserializer;
    /**
     * LessonController constructor.
     * @param LessonImplementation $lessonImplementation
     * @param CourseImplementation $courseImplementation
     * @param Deserializer $deserializer
     */
    public function __construct(
        LessonImplementation $lessonImplementation,
        CourseImplementation $courseImplementation,
        Deserializer $deserializer
    ) {
        $this->lessonImplementation = $lessonImplementation;
        $this->courseImplementation = $courseImplementation;
        $this->deserializer = $deserializer;
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_VIEW')")
     *
     * @param int $courseId
     * @return Response
     */
    public function indexAction(int $courseId): Response
    {
        $course = $this->courseImplementation->findCourse($courseId);

        if (!$course instanceof Course) {
            throw new NotFoundHttpException();
        }

        return $this->lessonImplementation->getListPresentation($course);
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param int $courseId
     * @return Response
     */
    public function createAction(int $courseId): Response
    {
        $course = $this->courseImplementation->findCourse($courseId);

        if (!$course instanceof Course) {
            throw new NotFoundHttpException();
        }

        return $this->lessonImplementation->createLesson($course);
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param int $courseId
     * @param int $lessonId
     * @return Response
     */
    public function editViewAction(int $courseId, int $lessonId)
    {
        $course = $this->courseImplementation->findCourse($courseId);

        if (!$course instanceof Course) {
            throw new NotFoundHttpException();
        }

        $lesson = $this->lessonImplementation->tryFind($lessonId);

        if (!$lesson instanceof Lesson) {
            throw new NotFoundHttpException();
        }

        return $this->lessonImplementation->editLessonView($course, $lesson);
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function newAction(Request $request): JsonResponse
    {
        $lessonMiddleware = new LessonMiddleware();
        $data = $lessonMiddleware->createNewLessonMiddleware(
            $request->request->all(),
            $this->courseImplementation,
            $this->lessonImplementation,
            $this->deserializer
        );

        $data['lessonView']->setUuid(Uuid::uuid4());

        return $this->lessonImplementation->newLesson(
            $data['course'],
            $data['lessonView']
        );
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAction(Request $request): JsonResponse
    {
        $lessonMiddleware = new LessonMiddleware();

        $data = $lessonMiddleware->createExistingLessonMiddleware(
            $request->request->all(),
            $this->lessonImplementation,
            $this->deserializer
        );

        return $this->lessonImplementation->updateLesson(
            $data['lessonView'],
            $data['lesson']
        );
    }
}