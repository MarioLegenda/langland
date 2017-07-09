<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\Language;
use AppBundle\Controller\Api\CommonOperationController;
use AppBundle\Entity\LearningUser;
use AppBundle\Entity\LearningUserCourse;
use AppBundle\Entity\LearningUserGame;
use AppBundle\Entity\LearningUserLesson;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends CommonOperationController
{
    public function dashboardAction(Request $request)
    {
        if ($request->getMethod() !== 'GET') {
            return $this->get('app_response_creator')->createMethodNotAllowedResponse();
        }

        return $this->render('::App/Dashboard/dashboard.html.twig');
    }

    public function coursePageDashboardAction($languageName, $languageId)
    {
        $em = $this->get('doctrine')->getManager();
        $learningUser = $this->getLearningUser();
        $responseCreator = $this->get('app_response_creator');

        if (!$learningUser instanceof LearningUser) {
            return $responseCreator->createResourceNotFoundResponse();
        }

        $language = $em->getRepository('AdminBundle:Language')->find($languageId);

        if (!$language instanceof Language) {
            return $responseCreator->createResourceNotFoundResponse();
        }

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

    public function learningUserLessonDashboardAction($courseName, $learningUserCourseId, $lessonName, $learningUserLessonId)
    {
        $learningUserLesson = $this->getRepository('AppBundle:LearningUserLesson')->find($learningUserLessonId);

        if ($learningUserLesson instanceof LearningUserLesson) {
            return $this->render('::App/Dashboard/dashboard.html.twig');
        }

        throw $this->createNotFoundException();
    }

    public function gamesListDashboardAction($courseName, $learningUserCourseId)
    {
        return $this->render('::App/Dashboard/dashboard.html.twig');
    }

    public function gameInitializeDashboardAction($courseName, $learningUserCourseId, $gameName, $learningUserGameId)
    {
        $learningUserGame = $this->getRepository('AppBundle:LearningUserGame')->find($learningUserGameId);

        if ($learningUserGame instanceof LearningUserGame) {
            return $this->render('::App/Dashboard/dashboard.html.twig');
        }

        throw $this->createNotFoundException();
    }
}
