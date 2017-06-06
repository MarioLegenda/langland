<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\LearningUserCourse;
use Symfony\Component\HttpFoundation\Request;

class CourseController extends CommonOperationController
{
    public function isInfoLookedAction()
    {
        $learningUser = $this->getLearningUser();

        $languageInfo = $this
            ->getRepository('AdminBundle:LanguageInfo')
            ->findByLanguage($learningUser->getCurrentLanguage());

        if ($languageInfo->getIsLooked() === false) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse();
    }

    public function markInfoLookedAction()
    {
        $learningUser = $this->getLearningUser();

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
        $learningUser = $this->getLearningUser();

        $languageInfo = $this
            ->getRepository('AdminBundle:LanguageInfo')
            ->findByLanguage($learningUser->getCurrentLanguage());

        $serialized = $this->serialize($languageInfo, array('language_info'));

        return $this->createSuccessJsonResponse($serialized);
    }

    public function findLanguageCoursesAction(Request $request)
    {
        $learningUser = $this->getLearningUser();

        $courseHolder = $learningUser->getCourseHolderByCurrentLanguage();

        $serialized = $this->serialize($courseHolder->getLearningUserCourses(), array('course_list'));

        return $this->createSuccessJsonResponse($serialized);
    }

    public function initAppAction($courseName, $courseHolderId)
    {
        $courseHolder = $this->getRepository('AppBundle:LearningUserCourse')->find($courseHolderId);

        if ($courseHolder instanceof LearningUserCourse) {
            return $this->render('::App/Dashboard/dashboard.html.twig');
        }

        throw $this->createNotFoundException();
    }
}