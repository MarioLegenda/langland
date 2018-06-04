<?php

namespace Library\Infrastructure\Helper;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

class SerializerWrapper
{
    /**
     * @var Serializer $serializer
     */
    private $serializer;
    /**
     * @var Deserializer $deserializer
     */
    private $deserializer;
    /**
     * @var ModelValidator $modelValidator
     */
    private $modelValidator;
    /**
     * SerializerWrapper constructor.
     * @param Serializer $serializer
     * @param Deserializer $deserializer
     * @param ModelValidator $modelValidator
     */
    public function __construct(
        Serializer $serializer,
        Deserializer $deserializer,
        ModelValidator $modelValidator
    ) {
        $this->serializer = $serializer;
        $this->deserializer = $deserializer;
        $this->modelValidator = $modelValidator;
    }
    /**
     * @param $object
     * @param array|string $groups
     * @param string $type
     * @return string
     */
    public function serialize($object, $groups, $type = 'json'): string
    {
        $context = new SerializationContext();
        $context->setGroups($this->normalizeGroups($groups));

        return $this->serializer->serialize($object, $type, $context);
    }
    /**
     * @param object $object
     * @param array|string $groups
     * @param string $type
     * @return array
     */
    public function normalize(object $object, $groups, $type = 'json'): array
    {
        $serialized = $this->serialize($object, $groups, $type);

        return json_decode($serialized, true);
    }
    /**
     * @param array $objectsArray
     * @param array $groups
     * @param string $type
     * @return string
     */
    public function serializeMany(array $objectsArray, array $groups, $type = 'json'): string
    {
        $arrayed = [];
        foreach ($objectsArray as $object) {
            $arrayed[] = json_decode($this->serialize($object, $groups, $type), true);
        }

        return json_encode($arrayed);
    }
    /**
     * @param object $object
     * @param array $groups
     * @param string $class
     * @param bool $validate
     * @return object
     */
    public function convertFromTo(
        object $object,
        array $groups,
        string $class,
        bool $validate = true
    ): object {
        $serialized = $this->serialize($object, $groups);

        $created = $this->getDeserializer()->create($serialized, $class);

        if ($validate) {
            $this->modelValidator->validate($created);
        }

        return $created;
    }
    /**
     * @return Deserializer
     */
    public function getDeserializer(): Deserializer
    {
        return $this->deserializer;
    }
    /**
     * @return ModelValidator
     */
    public function getModelValidator(): ModelValidator
    {
        return $this->modelValidator;
    }
    /**
     * @param array|string $groups
     * @return array
     */
    private function normalizeGroups($groups): array
    {
        if (!is_array($groups) and !is_string($groups)) {
            $message = sprintf(
                '$groups can only be an array or a string'
            );

            throw new \RuntimeException($message);
        }

        if (is_array($groups)) {
            return $groups;
        }

        if (is_string($groups)) {
            return [$groups];
        }
    }
}