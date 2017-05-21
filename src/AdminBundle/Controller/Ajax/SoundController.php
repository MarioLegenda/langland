<?php

namespace AdminBundle\Controller\Ajax;

use AdminBundle\Controller\RepositoryController;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\JsonResponse;

class SoundController extends RepositoryController
{
    public function findSoundsAction()
    {
        $sounds = $this->getRepository('AdminBundle:Sound')->findAll();

        $soundsArray = array();

        foreach ($sounds as $sound) {
            $context = SerializationContext::create();
            $context->setGroups(array('sounds'));

            $serialized = $this->get('jms_serializer')->serialize($sound, 'json', $context);

            $soundsArray[] = json_decode($serialized);
        }

        return new JsonResponse(array(
            'status' => 'success',
            'data' => $soundsArray,
        ));
    }
}