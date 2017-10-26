<?php

namespace Library\LearningMetadata\Business\Controller;

use Library\LearningMetadata\Business\Implementation\CourseImplementation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AdminBundle\Entity\Course;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CourseController
{
    /**
     * @var CourseImplementation
     */
    private $courseImplementation;

    public function __construct(
        CourseImplementation $courseImplementation
    ) {
        $this->courseImplementation = $courseImplementation;
    }
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->courseImplementation->getListPresentation();
    }
    /**
     * @return Response
     */
    public function createAction()
    {
        return $this->courseImplementation->getCreatePresentation();
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateAction(Request $request, $id)
    {
        return $this->courseImplementation->updateCourse($request, $id);
    }
    /**
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->courseImplementation->newCourse($request);
    }
    /**
     * @param int $courseId
     * @return Response
     */
    public function manageAction(int $courseId)
    {
        $course = $this->courseImplementation->findCourse($courseId);

        if (!$course instanceof Course) {
            throw new NotFoundHttpException();
        }

        return $this->courseImplementation->manageCourse($course);
    }
}