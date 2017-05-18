<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\LearningUser;

class LanguageController extends CommonOperationController
{
    public function findLearnableLanguagesAction()
    {
        $languages = $this->getRepository('AdminBundle:Language')->findLearnableLanguages();

        if (empty($languages)) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse(
            $this->createLanguages($languages, $this->getLearningUser())
        );
    }

    public function findLearningLanguagesAction()
    {
        $learningUser = $this->getLearningUser();

        if (!$learningUser instanceof LearningUser) {
            return $this->createFailedJsonResponse();
        }

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