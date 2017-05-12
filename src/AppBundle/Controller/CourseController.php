<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CourseController extends Controller
{
    public function dashboardAction($languageName, $id)
    {
        $em = $this->get('doctrine')->getManager();

        $language = $em->getRepository('AdminBundle:Language')->find($id);
        $learningUser = $em->getRepository('AppBundle:LearningUser')->findLearningUserByLanguage($language);

        if (!empty($learningUser)) {
            $learningUser->setCurrentLanguage($language);

            $em->persist($language);
            $em->flush();

            return $this->render('::App/Dashboard/dashboard.html.twig');
        }

        throw $this->createNotFoundException();
    }
}