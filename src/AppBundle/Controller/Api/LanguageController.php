<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\LearningUser;
use Symfony\Component\HttpFoundation\Request;

class LanguageController extends CommonOperationController
{
    public function getViewableAction(Request $request)
    {
        $responseCreator = $this->get('app_response_creator');

        if ($request->getMethod() !== 'GET') {
            return $responseCreator->createMethodNotAllowedResponse(array(
                'allowedMethods' => array('GET'),
            ));
        }

        $languages = $this->getRepository('AdminBundle:Language')->findViewableLanguages();

        if (empty($languages)) {
            return $responseCreator->createNoResourceResponse();
        }

        return $responseCreator->createSerializedResponse($this->createLanguages($languages, $this->getLearningUser()));
    }

    public function getStructuredAction(Request $request)
    {
        $responseCreator = $this->get('app_response_creator');

        if ($request->getMethod() !== 'GET') {
            return $responseCreator->createMethodNotAllowedResponse(array(
                'allowedMethods' => array('GET'),
            ));
        }

        $learningUser = $this->getLearningUser();

        if (!$learningUser instanceof LearningUser) {
            return $responseCreator->createNoResourceResponse();
        }

        $signedUpLanguages = $learningUser->getLanguages();

        if (empty($signedUpLanguages)) {
            return $responseCreator->createNoResourceResponse();
        }

        $currentLanguage = $learningUser->getCurrentLanguage();

        return $responseCreator->createSerializedResponse(array(
            'signedLanguages' => $this->serialize($signedUpLanguages, array('signed_courses')),
            'currentLanguage' => $this->serialize($currentLanguage, array('signed_courses')),
        ));
    }

    private function createLanguages(array $languages = array(), LearningUser $learningUser = null) : array
    {
        if (empty($languages)) {
            return array();
        }

        $data = array();

        foreach ($languages as $language) {
            $langArray = $this->serialize($language, array('viewable'));

            $image = $this->get('doctrine')->getRepository('AdminBundle:Image')->findBy(array(
                'language' => $language,
            ));

            if ($learningUser instanceof LearningUser) {
                $langArray['isLearning'] = ($learningUser->hasLanguage($language)) ? true : false;
            }

            if (!empty($image)) {
                $image = $image[0];

                $imgArray = $this->serialize($image, array('viewable'));

                $langArray['image'] = $imgArray;
            }

            $data[] = $langArray;
        }

        return $data;
    }
}