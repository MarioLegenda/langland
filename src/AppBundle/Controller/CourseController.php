<?php

namespace AppBundle\Controller;

use AppBundle\Controller\Api\CommonOperationController;

class CourseController extends CommonOperationController
{
    public function dashboardAction($languageName, $id)
    {
        $em = $this->get('doctrine')->getManager();

        $language = $em->getRepository('AdminBundle:Language')->find($id);

        $learningUser = $this->getLearningUser();
        $learningUser->setCurrentLanguage($language);

        $em->persist($learningUser);
        $em->flush();

        if (!empty($learningUser)) {
            return $this->render('::App/Dashboard/dashboard.html.twig');
        }

        throw $this->createNotFoundException();
    }
}