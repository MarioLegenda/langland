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
     * @param array $groups
     * @param string $type
     * @return string
     */
    public function serialize($object, array $groups, $type = 'json'): string
    {
        $context = new SerializationContext();
        $context->setGroups($groups);

        return $this->serializer->serialize($object, $type, $context);
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
     * @return object
     */
    public function convertFromTo(object $object, array $groups, string $class): object
    {
        $serialized = $this->serialize($object, $groups);

        $created = $this->getDeserializer()->create($serialized, $class);

        $this->modelValidator->validate($created);

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
}