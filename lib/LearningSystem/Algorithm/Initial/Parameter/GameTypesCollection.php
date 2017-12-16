<?php

namespace LearningSystem\Algorithm\Initial\Parameter;

use LearningSystem\Algorithm\Initial\Parameter\GameType\ImageWordGameTypeParameter;
use LearningSystem\Infrastructure\Observer\Observer;
use LearningSystem\Infrastructure\Observer\Subject;
use LearningSystem\Infrastructure\ParameterBagInterface;

class GameTypesCollection extends BaseAlgorithmParameter implements Observer
{
    /**
     * @var ParameterBagInterface $bag
     */
    private $bag;
    /**
     * @var iterable $dependencies
     */
    private $dependencies;

    public function __construct(
        string $name,
        ParameterBagInterface $bag,
        iterable $dependencies = []
    ) {
        $this->name = $name;
        $this->bag = $bag;
    }
    /**
     * @param Subject $subject
     * @param array $dependencies
     */
    public function update(Subject $subject, array $dependencies = []): void
    {
        $imageWordGameType1 = new ImageWordGameTypeParameter([
            'word_level' => 1,
            'include_time_trial' => false,
            'repeat' => true,
        ]);

        $this->metadata['game_types'][] = $imageWordGameType1;
    }
}