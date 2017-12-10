<?php

namespace LearningSystem\Algorithm\Initial\Parameter;

use LearningSystem\Infrastructure\Observer\Observer;
use LearningSystem\Infrastructure\ParameterBagInterface;
use LearningSystem\Infrastructure\Observer\Subject;

class GeneralParameters extends BaseAlgorithmParameter implements Observer
{
    /**
     * @var ParameterBagInterface $bag
     */
    private $bag;
    /**
     * GeneralParameters constructor.
     * @param string $name
     * @param ParameterBagInterface $bag
     */
    public function __construct(
        string $name,
        ParameterBagInterface $bag
    ) {
        $this->name = $name;
        $this->bag = $bag;
    }
    /**
     * @inheritdoc
     */
    public function update(Subject $subject, array $dependencies = null): void
    {
        $this->metadata = [
            'word_number' => 15,
            'word_level' => 1,
        ];

        $this->isProcessed = true;
    }
}