<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\LearningUserCourse;
use Symfony\Component\HttpFoundation\Request;

class CourseController extends CommonOperationController
{
    public function isInfoLookedAction(Request $request)
    {
        $learningUser = $this->getLearningUser($request->request->get('languageId'));

        $languageInfo = $this
            ->getRepository('AdminBundle:LanguageInfo')
            ->findByLanguage($learningUser->getCurrentLanguage());

        if ($languageInfo->getIsLooked() === false) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse();
    }

    public function markInfoLookedAction(Request $request)
    {
        $learningUser = $this->getLearningUser($request->request->get('languageId'));

        $languageInfo = $this
            ->getRepository('AdminBundle:LanguageInfo')
            ->findByLanguage($learningUser->getCurrentLanguage());

        $languageInfo->setIsLooked(true);

        $this->getManager()->persist($languageInfo);
        $this->getManager()->flush();

        return $this->createSuccessJsonResponse();
    }

    public function findLanguageInfosAction(Request $request)
    {
        $learningUser = $this->getLearningUser($request->request->get('languageId'));

        $languageInfo = $this
            ->getRepository('AdminBundle:LanguageInfo')
            ->findByLanguage($learningUser->getCurrentLanguage());

        $serialized = $this->serialize($languageInfo, array('language_info'));

        return $this->createSuccessJsonResponse($serialized);
    }

    public function findLanguageCoursesAction(Request $request)
    {
        $learningUser = $this->getLearningUser($request->request->get('languageId'));

        $courses = $this
            ->getRepository('AppBundle:LearningUserCourse')
            ->findCoursesByLearningUserDesc($learningUser);

        $serialized = $this->serialize($courses, array('course_list'));

        return $this->createSuccessJsonResponse($serialized);
    }

    public function initAppAction($languageName, $courseName, $courseId)
    {
        $learningUserCourse = $this->getRepository('AppBundle:LearningUserCourse')->findOneBy(array(
            'learningUser' => $this->getLearningUser(),
        ));

        if ($learningUserCourse instanceof LearningUserCourse) {
            return $this->render('::App/Dashboard/dashboard.html.twig');
        }

        throw $this->createNotFoundException();
    }
}