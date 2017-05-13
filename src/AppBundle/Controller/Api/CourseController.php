<?php

namespace AppBundle\Controller\Api;

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

    public function findLanguageInfosAction()
    {
        $learningUser = $this->getLearningUser();

        $languageInfo = $this
            ->getRepository('AdminBundle:LanguageInfo')
            ->findByLanguage($learningUser->getCurrentLanguage());

        $serialized = $this->serialize($languageInfo, array('language_info'));

        return $this->createSuccessJsonResponse($serialized);
    }

    public function findLanguageCoursesAction()
    {
        $learningUser = $this->getLearningUser();

        $courses = $this
            ->getRepository('AppBundle:LearningUserCourse')
            ->findCoursesByLearningUserDesc($learningUser);

        $serialized = $this->serialize($courses, array('course_list'));

        return $this->createSuccessJsonResponse($serialized);
    }
}