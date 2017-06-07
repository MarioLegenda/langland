<?php

namespace AppBundle\Controller;

use AppBundle\Controller\Api\CommonOperationController;
use AppBundle\Entity\LearningUser;
use AppBundle\Entity\LearningUserCourse;

class DashboardController extends CommonOperationController
{
    public function dashboardAction()
    {
        return $this->render('::App/Dashboard/dashboard.html.twig');
    }

    public function coursePageDashboardAction($languageName, $id)
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

    public function methodAppDashboardAction($courseName, $learningUserCourseId)
    {
        $learningUserCourse = $this->getRepository('AppBundle:LearningUserCourse')->find($learningUserCourseId);

        if ($learningUserCourse instanceof LearningUserCourse) {
            return $this->render('::App/Dashboard/dashboard.html.twig');
        }

        throw $this->createNotFoundException();
    }

    public function lessonListDashboardAction($courseName, $learningUserCourseId)
    {
        $learningUserCourse = $this->getRepository('AppBundle:LearningUserCourse')->find($learningUserCourseId);

        if ($learningUserCourse instanceof LearningUserCourse) {
            return $this->render('::App/Dashboard/dashboard.html.twig');
        }

        throw $this->createNotFoundException();
    }
}
