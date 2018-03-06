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
    /**
     * @param string|array $data
     * @param string|object $type
     * @param string|null $format
     * @void
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

        $errorsArray = [];
        $errorsString = null;
        if (count($errors) > 0) {
            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()] = $error->getMessage();
            }

            $errorsString = (string) $errors;
        }

        $this->errorsArray = $errorsArray;
        $this->errorsString = $errorsString;
        $this->object = $object;
    }
    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errorsString) or !empty($this->errorsArray);
    }
    /**
     * @return string
     */
    public function getErrorsString(): ?string
    {
        return $this->errorsString;
    }
    /**
     * @return array
     */
    public function getErrorsArray(): ?array
    {
        return $this->errorsArray;
    }
    /**
     * @return null|object
     */
    public function getSerializedObject()
    {
        return $this->object;
    }
}