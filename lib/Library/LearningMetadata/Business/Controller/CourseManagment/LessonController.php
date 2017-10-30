<?php

namespace Library\LearningMetadata\Business\Controller\CourseManagment;

use AdminBundle\Entity\Course;
use Library\LearningMetadata\Business\Implementation\CourseImplementation;
use Library\LearningMetadata\Business\Implementation\CourseManagment\LessonImplementation;
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
     * LessonController constructor.
     * @param LessonImplementation $lessonImplementation
     * @param CourseImplementation $courseImplementation
     */
    public function __construct(
        LessonImplementation $lessonImplementation,
        CourseImplementation $courseImplementation
    ) {
        $this->lessonImplementation = $lessonImplementation;
        $this->courseImplementation = $courseImplementation;
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

    public function createAction(int $courseId)
    {
        $course = $this->courseImplementation->findCourse($courseId);

        if (!$course instanceof Course) {
            throw new NotFoundHttpException();
        }

        return $this->lessonImplementation->createLesson($course);
    }
}