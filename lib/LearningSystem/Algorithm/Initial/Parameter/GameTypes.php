<?php

namespace LearningSystem\Algorithm\Initial\Parameter;

use LearningSystem\Algorithm\Initial\Parameter\Contract\ObserverDependencyInterface;
use LearningSystem\Infrastructure\Observer\Observer;
use LearningSystem\Infrastructure\Observer\Subject;
use LearningSystem\Infrastructure\ParameterBagInterface;

class GameTypes extends BaseAlgorithmParameter implements Observer, ObserverDependencyInterface
{
    /**
     * @var ParameterBagInterface $bag
     */
    private $bag;
    /**
     * @var array $dependencies
     */
    private $dependencies;
    /**
     * GeneralParameters constructor.
     * @param string $name
     * @param ParameterBagInterface $bag
     * @param array $dependencies
     */
    public function __construct(
        string $name,
        ParameterBagInterface $bag,
        array $dependencies = []
    ) {
        $this->name = $name;
        $this->dependencies = $dependencies;
        $this->bag = $bag;
    }
    /**
     * @inheritdoc
     */
    public function update(Subject $subject, array $dependencies = []): void
    {
        $generalParameters = $dependencies['general_parameters'];

        $this->metadata = [
            'blank' => [],
        ];

        $this->isProcessed = true;
    }
    /**
     * @inheritdoc
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }
    /**
     * @inheritdoc
     */
    public function hasDependencies(): bool
    {
        return !empty($this->dependencies);
    }
}