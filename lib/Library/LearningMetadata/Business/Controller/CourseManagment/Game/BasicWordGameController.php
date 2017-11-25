<?php

namespace Library\LearningMetadata\Business\Controller\CourseManagment\Game;

use AdminBundle\Entity\Course;
use Library\LearningMetadata\Business\Implementation\CourseImplementation;
use Library\LearningMetadata\Business\Implementation\CourseManagment\Game\BasicWordGameImplementation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BasicWordGameController
{
    /**
     * @var CourseImplementation $courseImplementation
     */
    private $courseImplementation;
    /**
     * @var BasicWordGameImplementation $basicWordGameImplementation
     */
    private $basicWordGameImplementation;

    public function __construct(
        CourseImplementation $courseImplementation,
        BasicWordGameImplementation $basicWordGameImplementation
    ) {
        $this->courseImplementation = $courseImplementation;
        $this->basicWordGameImplementation = $basicWordGameImplementation;
    }

    public function indexAction(int $courseId)
    {
        $course = $this->courseImplementation->tryFind($courseId);

        if (!$course instanceof Course) {
            throw new NotFoundHttpException();
        }

        return $this->basicWordGameImplementation->getListPresentation($course);
    }
}