<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\LearningUser;

class CommonOperationController extends ResponseController
{
    public function getLearningUser($languageId)
    {
        $language = $this->getRepository('AdminBundle:Language')->find($languageId);

        $learningUser = $this->getRepository('AppBundle:LearningUser')->findOneBy(array(
            'currentLanguage' => $language,
        ));

        if (!$learningUser instanceof LearningUser) {
            return null;
        }

        return $learningUser;
    }
}