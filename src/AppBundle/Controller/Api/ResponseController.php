<?php

namespace AppBundle\Controller\Api;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseController extends Controller
{
    /**
     * @param string $repository
     * @return EntityRepository
     */
    protected function getRepository(string $repository) : EntityRepository
    {
        return $this->get('doctrine')->getRepository($repository);
    }
    /**
     * @return EntityManager
     */
    protected function getManager() : EntityManager
    {
        return $this->get('doctrine')->getManager();
    }
    /**
     * @param array $data
     * @return JsonResponse
     */
    protected function createSuccessJsonResponse(array $data = array()) : JsonResponse
    {
        return new JsonResponse(array(
            'status' => 'success',
            'data' => $data,
        ));
    }
    /**
     * @param array $data
     * @return JsonResponse
     */
    protected function createFailedJsonResponse(array $data = array()) : JsonResponse
    {
        return new JsonResponse(array(
            'status' => 'failure',
            'data' => $data,
        ));
    }
    /**
     * @param array $data
     * @return JsonResponse
     */
    protected function createErrorJsonResponse(array $data = array()) : JsonResponse
    {
        return new JsonResponse(array(
            'status' => 'error',
            'data' => $data,
        ));
    }
    /**
     * @param $data
     * @param array|null $groups
     * @return array
     */
    protected function serialize($data, array $groups = null) : array
    {
        $context = null;

        if (!empty($groups)) {
            $context = SerializationContext::create();
            $context->setGroups($groups);
        }

        if (is_array($data)) {
            return $this->serializeSimpleArray($data, $context);
        }

        $serialized = $this->get('jms_serializer')->serialize($data, 'json', $context);

        return json_decode($serialized, true);
    }

    private function serializeSimpleArray(array $data, SerializationContext $context = null) : array
    {
        $serialized = array();
        foreach ($data as $object) {
            if (!is_object($object)) {
                return null;
            }

            $serialized[] = json_decode($this->get('jms_serializer')->serialize($object, 'json', $context));
        }

        return $serialized;
    }
}