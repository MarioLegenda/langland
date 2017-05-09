<?php

namespace AppBundle\Controller\Api;

class LanguageController extends ResponseController
{
    public function findLearnableLanguagesAction()
    {
        $dbLanguages = $this->get('doctrine')->getRepository('AdminBundle:Language')->findLearnableLanguages();

        if (empty($dbLanguages)) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse($this->createLanguages($dbLanguages));
    }

    private function createLanguages(array $languages) : array
    {
        $data = array();
        foreach ($languages as $language) {
            $langArray = $this->serialize($language, array('learnable_language'));

            $image = $this->get('doctrine')->getRepository('AdminBundle:Image')->findBy(array(
                'language' => $language,
            ));

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