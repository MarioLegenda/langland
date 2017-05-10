<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\LearningUser;

class LanguageController extends ResponseController
{
    public function findLearnableLanguagesAction()
    {
        $dbLanguages = $this->getRepository('AdminBundle:Language')->findLearnableLanguages();
        $learningUser = $this->getRepository('AppBundle:LearningUser')->findLearningUserByLoggedInUser($this->getUser());

        if (empty($dbLanguages)) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse(
            $this->createLanguages($dbLanguages, $learningUser)
        );
    }

    public function findLearningLanguagesAction()
    {
        $learningUser = $this->getRepository('AppBundle:LearningUser')->findBy(array(
            'user' => $this->getUser(),
        ));

        if (empty($learningUser)) {
            return $this->createFailedJsonResponse();
        }

        $learningUser = $learningUser[0];

        $signedUpLanguages = $learningUser->getLanguages();

        if (empty($signedUpLanguages)) {
            return $this->createSuccessJsonResponse();
        }

        $currentLanguage = $learningUser->getCurrentLanguage();

        return $this->createSuccessJsonResponse(array(
            'signedLanguages' => $this->serialize($signedUpLanguages, array('signed_courses')),
            'currentLanguage' => $this->serialize($currentLanguage, array('signed_courses')),
        ));
    }

    private function createLanguages(array $languages, LearningUser $learningUser = null) : array
    {
        $data = array();
        foreach ($languages as $language) {
            $langArray = $this->serialize($language, array('learnable_language'));

            $image = $this->get('doctrine')->getRepository('AdminBundle:Image')->findBy(array(
                'language' => $language,
            ));

            if ($learningUser instanceof LearningUser) {
                $langArray['isLearning'] = ($learningUser->hasLanguage($language)) ? true : false;
            }

            if (!empty($image)) {
                $image = $image[0];

                $imgArray = $this->serialize($image, array('learnable_language'));

                $langArray['image'] = $imgArray;
            }

            $data[] = $langArray;
        }

        return $data;
    }
}