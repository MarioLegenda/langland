<?php

namespace LearningSystem\Middleware\Initial;

use LearningSystem\Algorithm\Initial\Parameter\Contract\AlgorithmParameterInterface;
use LearningSystem\Algorithm\Initial\Parameter\Contract\ObserverDependencyInterface;
use LearningSystem\Infrastructure\Observer\ObservableAccessInterface;
use LearningSystem\Infrastructure\Observer\Observer;
use LearningSystem\Infrastructure\Observer\Subject;
use LearningSystem\Infrastructure\ParameterBagInterface;

class ParameterConverter implements Subject, ObservableAccessInterface
{
    /**
     * @var array $observers
     */
    private $observers = [];
    /**
     * @inheritdoc
     */
    public function attach(Observer $observer): Subject
    {
        $this->observers[$observer->getName()] = $observer;

        return $this;
    }
    /**
     * @inheritdoc
     */
    public function notify(\Closure $postUpdateEvent = null): void
    {
        /** @var ObserverDependencyInterface|Observer|Subject $observer */
        foreach ($this->observers as $observer) {
            if ($observer instanceof ObserverDependencyInterface and $observer->hasDependencies()) {
                $dependencies = $this->updateDependencies($observer->getDependencies());

                $observer->update($this, $dependencies);

                continue;
            }

            $observer->update($this);

            if ($postUpdateEvent instanceof \Closure) {
                $postUpdateEvent->__invoke($this, $observer);
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function getObserver(string $name): Observer
    {
        return $this->observers[$name];
    }
    /**
     * @inheritdoc
     */
    public function hasObserver(string $name): bool
    {
        return array_key_exists($name, $this->observers);
    }

    /**
     * @param array $dependencies
     * @return array
     */
    private function updateDependencies(array $dependencies)
    {
        $processed = [];
        /** @var string $dependency */
        foreach ($dependencies as $dependency) {
            if ($this->hasObserver($dependency)) {
                /** @var Observer|AlgorithmParameterInterface $observer */
                $observer = $this->getObserver($dependency);

                if (!$observer->isProcessed()) {
                    if ($observer instanceof ObserverDependencyInterface and $observer->hasDependencies()) {
                        $resolvedDependencies = $this->updateDependencies($observer->getDependencies());

                        $observer->update($this, $resolvedDependencies);

                        continue;
                    }

                    $observer->update($this);

                    $processed[$observer->getName()] = $observer;
                } else if ($observer->isProcessed()) {
                    $processed[$observer->getName()] = $observer;
                }
            }
        }

        return $processed;
    }
}