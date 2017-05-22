<?php

namespace AdminBundle\Controller\Ajax;

use JMS\Serializer\SerializationContext;
use Library\ResponseController;

class GameController extends ResponseController
{
    public function findGameWordsAction($courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);
        $language = $course->getLanguage();

        $words = $this->getRepository('AdminBundle:Word')->findBy(array(
            'language' => $language,
        ));

        if (empty($words)) {
            return $this->createFailureJsonResponse();
        }

        return $this->createSuccessJsonResponse($this->serialize($words, array('words_by_language')));
    }
}