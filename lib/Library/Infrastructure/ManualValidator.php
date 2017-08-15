<?php

namespace Library\Infrastructure;

use Symfony\Component\HttpFoundation\JsonResponse;

class ManualValidator
{
    /**
     * @var $validator
     */
    private $validator;
    /**
     * ManualValidator constructor.
     * @param $validator
     */
    public function __construct($validator)
    {
        $this->validator = $validator;
    }
    /**
     * @param $entity
     * @return bool|JsonResponse
     */
    public function validate($entity)
    {
        $violations = $this->validator->validate($entity);

        if (count($violations) > 0) {
            $errors = array();
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }

            return new JsonResponse(array(
                'status' => 'error',
                'data' => $errors,
            ));
        }

        return true;
    }
}