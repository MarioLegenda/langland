<?php

namespace Library\Infrastructure\Helper;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

class CommonSerializer
{
    /**
     * @var Serializer $serializer
     */
    private $serializer;
    /**
     * CommonSerializer constructor.
     * @param Serializer $serializer
     */
    public function __construct(
        Serializer $serializer
    ) {
        $this->serializer = $serializer;
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
}