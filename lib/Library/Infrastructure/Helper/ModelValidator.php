<?php

namespace Library\Infrastructure\Helper;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ModelValidator
{
    /**
     * @var array $errorsArray
     */
    private $errorsArray;
    /**
     * @var string $errorsString
     */
    private $errorsString;
    /**
     * @var ValidatorInterface $validator
     */
    private $validator;
    /**
     * ModelValidator constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    /**
     * @param object $object
     */
    public function tryValidate(object $object)
    {
        $errors = $this->validator->validate($object);

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
    }
    /**
     * @param object $object
     * @throws \RuntimeException
     */
    public function validate(object $object)
    {
        $this->tryValidate($object);

        if ($this->hasErrors()) {
            throw new \RuntimeException($this->getErrorsString());
        }
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
}