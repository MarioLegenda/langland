<?php

namespace Library\LearningMetadata\Business\Controller;

use Library\LearningMetadata\Business\Implementation\CourseImplementation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AdminBundle\Entity\Course;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @Security("has_role('ROLE_ALLOWED_VIEW')")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->courseImplementation->getListPresentation();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @return Response
     */
    public function createAction()
    {
        return $this->courseImplementation->getCreatePresentation();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateAction(Request $request, $id)
    {
        return $this->courseImplementation->updateCourse($request, $id);
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->courseImplementation->newCourse($request);
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
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