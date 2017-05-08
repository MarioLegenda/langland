<?php

namespace AppBundle\Controller\Api;

use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class LanguageController extends Controller
{
    public function findLearnableLanguagesAction()
    {
        $dbLanguages = $this->get('doctrine')->getRepository('AdminBundle:Language')->findLearnableLanguages();

        if (empty($dbLanguages)) {
            return new JsonResponse(array(
                'status' => 'failure',
                'data' => array(),
            ));
        }

        $data = array();
        foreach ($dbLanguages as $language) {
            $context = SerializationContext::create();
            $context->setGroups(array('learnable_language'));
            $serialized = $this->get('jms_serializer')->serialize($language, 'json', $context);

            $data[] = json_decode($serialized, true);
        }

        return new JsonResponse(array(
            'status' => 'success',
            'data' => $data,
        ));
    }
}