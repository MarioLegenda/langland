<?php

namespace AppBundle\Controller\Api;

class CourseController extends ResponseController
{
    public function isInfoLookedAction()
    {
        $learningUser = $this
            ->getRepository('AppBundle:LearningUser')
            ->findLearningUserByLoggedInUser($this->getUser());

        $languageInfo = $this
            ->getRepository('AdminBundle:LanguageInfo')
            ->findByLanguage($learningUser->getCurrentLanguage());

        if ($languageInfo->getIsLooked() === false) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse();
    }

    public function findLanguageInfosAction()
    {
        $learningUser = $this
            ->getRepository('AppBundle:LearningUser')
            ->findLearningUserByLoggedInUser($this->getUser());

        $languageInfo = $this
            ->getRepository('AdminBundle:LanguageInfo')
            ->findByLanguage($learningUser->getCurrentLanguage());

        $serialized = $this->serialize($languageInfo, array('language_info'));

        return $this->createSuccessJsonResponse($serialized);
    }
}