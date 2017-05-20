<?php

namespace AppBundle\Controller;

use AppBundle\Controller\Api\CommonOperationController;
use AppBundle\Entity\LearningUser;

class CourseController extends CommonOperationController
{
    public function dashboardAction($languageName, $id)
    {
        $em = $this->get('doctrine')->getManager();

        $learningUser = $this->getLearningUser();

        if (!$learningUser instanceof LearningUser) {
            throw $this->createNotFoundException();
        }

        $language = $em->getRepository('AdminBundle:Language')->find($id);

        $learningUser->setCurrentLanguage($language);

        $em->persist($learningUser);
        $em->flush();

        if (!empty($learningUser)) {
            return $this->render('::App/Dashboard/dashboard.html.twig');
        }

        throw $this->createNotFoundException();
    }
}