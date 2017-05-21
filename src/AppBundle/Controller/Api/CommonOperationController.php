<?php

namespace AppBundle\Controller\Api;

use Library\ResponseController;
use AppBundle\Entity\LearningUser;

class CommonOperationController extends ResponseController
{
    public function getLearningUser()
    {
        $learningUser = $this->getRepository('AppBundle:LearningUser')->findOneBy(array(
            'user' => $this->getUser(),
        ));

        if (!$learningUser instanceof LearningUser) {
            return null;
        }

        return $learningUser;
    }
}