<?php

namespace LearningSystem\Input;

use LearningSystem\Exception\ParameterException;
use LearningSystem\Infrastructure\Validator\ValidatorInterface;
use LearningSystem\Infrastructure\BaseParameterBag;

class InitialDataParameterBag extends BaseParameterBag
{
    /**
     * InitialParameterBag constructor.
     * @param array $parameters
     * @param array $validators
     * @throws ParameterException
     */
    public function __construct(
        array $parameters = [],
        array $validators = []
    ) {
        parent::__construct($parameters);

        if (!empty($validators)) {
            /** @var ValidatorInterface $validator */
            foreach ($validators as $validator) {
                if (!$validator instanceof ValidatorInterface) {
                    $message = sprintf(
                        'Given validator %s is not a %s type in %s',
                        get_class($validator),
                        ValidatorInterface::class,
                        InitialDataParameterBag::class
                    );

                    throw new ParameterException($message);
                }

                $validator->validate($this);
            }
        }
    }
}