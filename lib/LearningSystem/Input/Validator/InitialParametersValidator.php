<?php

namespace LearningSystem\Input\Validator;

use Assert\Assertion;
use Assert\LazyAssertionException;
use LearningSystem\Exception\ParameterException;
use LearningSystem\Infrastructure\ParameterBagInterface;
use LearningSystem\Infrastructure\Validator\ValidatorInterface;
use PHPUnit\Framework\AssertionFailedError;

class InitialParametersValidator implements ValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function validate(object $bag)
    {
        if (!$bag instanceof ParameterBagInterface) {
            $message = sprintf(
                'InitialParametersValidator expected an instance of %s to validate with, got something else',
                get_class(ParameterBagInterface::class)
            );

            throw new ParameterException($message);
        }

        $this->validateSpeakingLanguagesParameter($bag);
        $this->validateProfession($bag);
        $this->validatePersonType($bag);
        $this->validateLearningTime($bag);
        $this->validateFreeTime($bag);
        $this->validateMemory($bag);
        $this->validateChallenges($bag);
    }
    /**
     * @param ParameterBagInterface $bag
     */
    private function validateSpeakingLanguagesParameter(ParameterBagInterface $bag)
    {
        $this->genericValidation('speaking_languages', $bag);

        $parameters = $bag->get('speaking_languages')['parameter'];

        Assertion::integer($parameters['value']);
    }
    /**
     * @param ParameterBagInterface $bag
     */
    private function validateProfession(ParameterBagInterface $bag)
    {
        $this->genericValidation('profession', $bag);

        $parameters = $bag->get('profession')['parameter'];

        Assertion::string($parameters['value']);
    }
    /**
     * @param ParameterBagInterface $bag
     */
    public function validatePersonType(ParameterBagInterface $bag)
    {
        $this->genericValidation('person_type', $bag);

        $this->genericValidation('person_type', $bag);

        $parameters = $bag->get('person_type')['parameter'];

        Assertion::integer($parameters['value']);
    }
    /**
     * @param ParameterBagInterface $bag
     */
    public function validateLearningTime(ParameterBagInterface $bag)
    {
        $this->genericValidation('learning_time', $bag);

        $parameters = $bag->get('learning_time')['parameter'];

        Assertion::string($parameters['value']);
    }
    /**
     * @param ParameterBagInterface $bag
     */
    public function validateFreeTime(ParameterBagInterface $bag)
    {
        $this->genericValidation('free_time', $bag);

        $parameters = $bag->get('free_time')['parameter'];

        Assertion::integer($parameters['value']);
    }
    /**
     * @param ParameterBagInterface $bag
     */
    public function validateMemory(ParameterBagInterface $bag)
    {
        $this->genericValidation('memory', $bag);

        $parameters = $bag->get('memory')['parameter'];

        Assertion::integer($parameters['value']);

        Assertion::lessOrEqualThan($parameters['value'], 2);
    }
    /**
     * @param ParameterBagInterface $bag
     */
    public function validateChallenges(ParameterBagInterface $bag)
    {
        $this->genericValidation('challenges', $bag);

        $parameters = $bag->get('challenges')['parameter'];

        Assertion::integer($parameters['value']);

        Assertion::lessOrEqualThan($parameters['value'], 1);
    }

    public function validateStressfulJob(ParameterBagInterface $bag)
    {
        $this->genericValidation('stressful_job', $bag);

        $parameters = $bag->get('stressful_job')['parameter'];

        Assertion::integer($parameters['value']);

        Assertion::lessOrEqualThan($parameters['value'], 1);
    }
    /**
     * @param string $key
     * @param ParameterBagInterface $bag
     */
    private function genericValidation(string $key, ParameterBagInterface $bag)
    {
        Assertion::true($bag->has($key));

        $value = $bag->get($key);

        Assertion::notNull($value);
        Assertion::isArray($value);
        Assertion::keyExists($value, 'parameter');

        $parameters = $value['parameter'];

        Assertion::keyExists($parameters, 'name');
        Assertion::keyExists($parameters, 'question');
        Assertion::keyExists($parameters, 'value');

        Assertion::eq($key, $parameters['name']);
    }
}