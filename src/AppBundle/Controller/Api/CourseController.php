<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\LearningUser;
use Symfony\Component\HttpFoundation\Request;

class CourseController extends CommonOperationController
{
    public function isInfoLookedAction(Request $request)
    {
        if ($request->getMethod() !== 'GET') {
            return $this->get('app_response_creator')->createMethodNotAllowedResponse();
        }

        $responseCreator = $this->get('app_response_creator');
        $learningUser = $this->getLearningUser();

        if (!$learningUser instanceof LearningUser) {
            return $responseCreator->createResourceForbiddenResponse();
        }

        $languageInfo = $this
            ->getRepository('AdminBundle:LanguageInfo')
            ->findByLanguage($learningUser->getCurrentLanguage());

        if (empty($languageInfo)) {
            return $responseCreator->createNoResourceResponse();
        }

        if ($languageInfo->getIsLooked() === false) {
            return $responseCreator->createNoResourceResponse();
        }

        return $responseCreator->createResourceAvailableResponse();
    }

    public function markInfoLookedAction(Request $request)
    {
        if ($request->getMethod() !== 'POST') {
            return $this->get('app_response_creator')->createMethodNotAllowedResponse();
        }

        $responseCreator = $this->get('app_response_creator');
        $learningUser = $this->getLearningUser();

        if (!$learningUser instanceof LearningUser) {
            return $responseCreator->createResourceForbiddenResponse();
        }

        $languageInfo = $this
            ->getRepository('AdminBundle:LanguageInfo')
            ->findByLanguage($learningUser->getCurrentLanguage());

        $languageInfo->setIsLooked(true);

        $this->getManager()->persist($languageInfo);
        $this->getManager()->flush();

        return $responseCreator->createResourceAvailableResponse();
    }

    public function findLanguageInfosAction(Request $request)
    {
        if ($request->getMethod() !== 'GET') {
            return $this->get('app_response_creator')->createMethodNotAllowedResponse();
        }

        $responseCreator = $this->get('app_response_creator');
        $learningUser = $this->getLearningUser();

        if (!$learningUser instanceof LearningUser) {
            return $responseCreator->createResourceForbiddenResponse();
        }

        $languageInfo = $this
            ->getRepository('AdminBundle:LanguageInfo')
            ->findByLanguage($learningUser->getCurrentLanguage());

        return $responseCreator->createSerializedResponse($languageInfo, array('language_info'));
    }

    public function findLanguageCoursesAction(Request $request)
    {
        if ($request->getMethod() !== 'GET') {
            return $this->get('app_response_creator')->createMethodNotAllowedResponse();
        }

        $responseCreator = $this->get('app_response_creator');
        $learningUser = $this->getLearningUser();

        if (!$learningUser instanceof LearningUser) {
            return $responseCreator->createResourceForbiddenResponse();
        }

        $courseHolder = $learningUser->getCourseHolderByCurrentLanguage();

        return $responseCreator->createSerializedResponse($courseHolder->getLearningUserCourses(), array('course_list'));    }
}