<?php

namespace Library\Infrastructure\Helper;

use JMS\Serializer\Serializer;
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
    /**
     * @param string|array $data
     * @param string|object $type
     * @param string|null $format
     * @return object
     */
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

        $object = $this->serializer->deserialize($data, $type, $format);

        $errors = $this->validation->validate($object);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            throw new \RuntimeException($errorsString);
        }

        return $object;
    }
}