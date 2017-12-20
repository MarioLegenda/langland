<?php

namespace LearningSystem\Algorithm\Initial\Parameter;

use LearningSystem\Algorithm\Initial\Parameter\Contract\AlgorithmParameterInterface;
use LearningSystem\Infrastructure\BaseParameterBag;
use LearningSystem\Infrastructure\Observer\ObservableAccessInterface;
use LearningSystem\Infrastructure\Sort\Contract\SortableObjectInterface;
use LearningSystem\Infrastructure\Sort\Sort;

class AlgorithmParameters extends BaseParameterBag
{
    private $mandatoryParameters = [
        'general_parameters',
        'game_types',
    ];

    public function __construct(ObservableAccessInterface $subject)
    {
        $this->validateSubject($subject);
        $parameters = $this->extractParameters($subject);

        parent::__construct($parameters);
    }
    /**
     * @param ObservableAccessInterface $subject
     * @throws \RuntimeException
     */
    private function validateSubject(ObservableAccessInterface $subject): void
    {
        foreach ($this->mandatoryParameters as $mandatoryParameter) {
            if (!$subject->hasObserver($mandatoryParameter)) {
                $message = sprintf(
                    '%s does not have all the mandatory parameters for %s. %s was not found. Mandatory parameters are %s',
                    get_class($subject),
                    get_class($this),
                    $mandatoryParameter,
                    implode(', ', $this->mandatoryParameters)
                );

                throw new \RuntimeException($message);
            }
        }
    }
    /**
     * @param ObservableAccessInterface $subject
     * @return array
     */
    private function extractParameters(ObservableAccessInterface $subject): array
    {
        $parameters = [];
        foreach ($this->mandatoryParameters as $mandatoryParameter) {
            /** @var AlgorithmParameterInterface $observer */
            $observer = $subject->getObserver($mandatoryParameter);

            $metadata = $observer->getMetadata();

            if ($observer instanceof SortableObjectInterface) {
                $metadata = Sort::init()->sortParameterBag($observer->getMarker(), $metadata);
            }

            $parameters[$observer->getName()] = $metadata;
        }

        return $parameters;
    }
}