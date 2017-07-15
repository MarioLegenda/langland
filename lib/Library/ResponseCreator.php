<?php

namespace Library;

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
     * @param mixed $content
     * @return JsonResponse
     */
    public function createMethodNotAllowedResponse($content = null) : JsonResponse
    {
        return new JsonResponse($content, 405);
    }
    /**
     * @return JsonResponse
     */
    public function createNoResourceResponse() : JsonResponse
    {
        return new JsonResponse(null, 204);
    }
    /**
     * @param $content
     * @return JsonResponse
     */
    public function createResourceAvailableResponse($content = null) : JsonResponse
    {
        return new JsonResponse($content, 200);
    }
    /**
     * @param null $content
     * @return JsonResponse
     */
    public function createResourceCreatedResponse($content = null) : JsonResponse
    {
        return new JsonResponse($content, 201);
    }
    /**
     * @return JsonResponse
     */
    public function createResourceNotFoundResponse() : JsonResponse
    {
        return new JsonResponse(null, 404);
    }
    /**
     * @return JsonResponse
     */
    public function createSeeOtherResponse() : JsonResponse
    {
        return new JsonResponse(null, 303);
    }
    /**
     * @return JsonResponse
     */
    public function createBadRequestResponse() : JsonResponse
    {
        return new JsonResponse(null, 400);
    }
    /**
     * @return JsonResponse
     */
    public function createResourceForbiddenResponse() : JsonResponse
    {
        return new JsonResponse(null, 403);
    }
    /**
     * @param array $content
     * @param array|null $serializationGroups
     * @return JsonResponse
     */
    public function createSerializedResponse($content = null, array $serializationGroups = null) : JsonResponse
    {
        if (empty($content)) {
            return $this->createNoResourceResponse();
        }

        if (!is_null($serializationGroups)) {
            $content = $this->serialize($content, $serializationGroups);
        }

        return $this->createResourceAvailableResponse($content);
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