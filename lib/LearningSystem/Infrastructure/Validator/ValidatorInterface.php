<?php

namespace LearningSystem\Infrastructure\Validator;

interface ValidatorInterface
{
    /**
     * @param object $toValidate
     * @return mixed
     */
    public function validate(object $toValidate);
}