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

            $langArray = json_decode($serialized, true);

            $image = $this->get('doctrine')->getRepository('AdminBundle:Image')->findBy(array(
                'language' => $language,
            ));

            if (!empty($image)) {
                $image = $image[0];

                $context = SerializationContext::create();
                $context->setGroups(array('learnable_language'));
                $serialized = $this->get('jms_serializer')->serialize($image, 'json', $context);

                $imgArray = json_decode($serialized, true);

                $langArray['image'] = $imgArray;
            }

            $data[] = $langArray;
        }

        return new JsonResponse(array(
            'status' => 'success',
            'data' => $data,
        ));
    }
}