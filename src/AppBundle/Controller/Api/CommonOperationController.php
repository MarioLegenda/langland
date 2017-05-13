<?php

namespace AppBundle\Controller\Api;

class CommonOperationController extends ResponseController
{
    public function getLearningUser()
    {
        return $this
            ->getRepository('AppBundle:LearningUser')
            ->findLearningUserByLoggedInUser($this->getUser());
    }
}