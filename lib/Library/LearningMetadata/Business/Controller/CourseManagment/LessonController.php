<?php

namespace Library\LearningMetadata\Business\Controller\CourseManagment;

use AdminBundle\Entity\Course;
use Library\Infrastructure\Helper\Deserializer;
use Library\LearningMetadata\Business\Implementation\CourseImplementation;
use Library\LearningMetadata\Business\Implementation\CourseManagment\LessonImplementation;
use Library\LearningMetadata\Business\Middleware\LessonMiddleware;
use Library\LearningMetadata\Business\ViewModel\Lesson\LessonView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @param int $courseId
     * @return Response
     */
    public function indexAction(int $courseId)
    {
        $course = $this->courseImplementation->findCourse($courseId);

        if (!$course instanceof Course) {
            throw new NotFoundHttpException();
        }

        return $this->lessonImplementation->getListPresentation($course);
    }
    /**
     * @param int $courseId
     * @return Response
     */
    public function createAction(int $courseId)
    {
        $course = $this->courseImplementation->findCourse($courseId);

        if (!$course instanceof Course) {
            throw new NotFoundHttpException();
        }

        return $this->lessonImplementation->createLesson($course);
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function newAction(Request $request)
    {
        $lessonMiddleware = new LessonMiddleware();
        $data = $lessonMiddleware->createNewLessonMiddleware(
            $request,
            $this->courseImplementation,
            $this->deserializer
        );

        return $this->lessonImplementation->newLesson(
            $data['course'],
            $data['lessonView']
        );
    }
}