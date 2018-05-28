<?php

namespace Library\Infrastructure\Helper;

use JMS\Serializer\Serializer;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Deserializer
{
    /**
     * @var Serializer $serializer
     */
    private $serializer;
    /**
     * @var Validation $validation
     */
    private $validation;
    /**
     * @var string $errorsString
     */
    private $errorsString;
    /**
     * @var array $errorsArray
     */
    private $errorsArray;
    /**
     * @var object|null $object
     */
    private $object;
    /**
     * Deserializer constructor.
     * @param Serializer $serializer
     * @param ValidatorInterface $validation
     */
    public function __construct(
        Serializer $serializer,
        ValidatorInterface $validation
    ) {
        $this->serializer = $serializer;
        $this->validation = $validation;
    }

    public function create($data, $type, string $format = null)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }

        if (!is_string($format)) {
            $format = 'json';
        }

        if (!is_string($type) and !is_object($type)) {
            throw new \RuntimeException('$type has to be a string or an object');
        }

        if (is_object($type)) {
            $type = get_class($type);
        }

        return $this->serializer->deserialize($data, $type, $format);
    }
    /**
     * @return null|object
     */
    public function getSerializedObject()
    {
        return $this->object;
    }
}