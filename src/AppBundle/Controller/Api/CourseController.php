<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\LearningUser;

class CourseController extends ResponseController
{
    public function findSignedCoursesAction()
    {
        $repo = $this->get('doctrine')->getRepository('AppBundle:LearningUser');

        $learningUser = $repo->findLearningUserByLoggedInUser($this->getUser());

        if (!$learningUser instanceof LearningUser) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse(
            $this->serialize($learningUser, array('course_data'))
        );
    }
}