<?php

namespace AppBundle\Rest;

use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

class ResponseCreator
{
    /**
     * @var Serializer $serializer
     */
    private $serializer;
    /**
     * ResponseCreator constructor.
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }
    /**
     * @return JsonResponse
     */
    public function createMethodNotAllowedResponse() : JsonResponse
    {
        return new JsonResponse(null, 405);
    }
    /**
     * @return JsonResponse
     */
    public function createNoContentResponse() : JsonResponse
    {
        return new JsonResponse(null, 204);
    }
    /**
     * @param $content
     * @return JsonResponse
     */
    public function createContentAvailableResponse($content) : JsonResponse
    {
        return new JsonResponse($content, 200);
    }
    /**
     * @return JsonResponse
     */
    public function createBadRequestResponse() : JsonResponse
    {
        return new JsonResponse(null, 400);
    }
    /**
     * @param array $content
     * @param array|null $serializationGroups
     * @return JsonResponse
     */
    public function createSerializedResponse($content = null, array $serializationGroups = null) : JsonResponse
    {
        if (empty($content)) {
            return $this->createNoContentResponse();
        }

        if (!is_null($serializationGroups)) {
            $content = $this->serialize($content, $serializationGroups);
        }

        return $this->createContentAvailableResponse($content);
    }

    private function serialize($data, array $groups = null) : array
    {
        $context = null;

        if (is_array($data)) {
            return $this->serializeSimpleArray($data, $groups);
        }

        if (!empty($groups)) {
            $context = SerializationContext::create();
            $context->setGroups($groups);
        }

        $serialized = $this->serializer->serialize($data, 'json', $context);

        return json_decode($serialized, true);
    }

    private function serializeSimpleArray(array $data, array $groups = null) : array
    {
        $serialized = array();
        foreach ($data as $object) {
            if (!is_object($object)) {
                return null;
            }

            $context = null;

            if (!empty($groups)) {
                $context = SerializationContext::create();
                $context->setGroups($groups);
            }

            $serialized[] = json_decode($this->serializer->serialize($object, 'json', $context));
        }

        return $serialized;
    }
}