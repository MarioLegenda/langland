<?php

namespace LearningSystem\Algorithm\Initial\Parameter;

use LearningSystem\Algorithm\Initial\Parameter\Contract\ObserverDependencyInterface;
use LearningSystem\Algorithm\Initial\Parameter\GameType\ImageWordGameTypeParameter;
use LearningSystem\Algorithm\Initial\Parameter\GameType\SentenceGameTypeParameter;
use LearningSystem\Infrastructure\Observer\Observer;
use LearningSystem\Infrastructure\Observer\Subject;
use LearningSystem\Infrastructure\ParameterBagInterface;
use LearningSystem\Infrastructure\Sort\Contract\SortableObjectInterface;
use LearningSystem\Infrastructure\Type\RepeatType;
use LearningSystem\Infrastructure\Type\TimeTrialType;

class GameTypesCollection extends BaseAlgorithmParameter implements Observer, ObserverDependencyInterface, SortableObjectInterface
{
    /**
     * @var ParameterBagInterface $bag
     */
    private $bag;
    /**
     * @var iterable $dependencies
     */
    private $dependencies;
    /**
     * GameTypesCollection constructor.
     * @param string $name
     * @param ParameterBagInterface $bag
     * @param iterable $dependencies
     */
    public function __construct(
        string $name,
        ParameterBagInterface $bag,
        iterable $dependencies = []
    ) {
        $this->name = $name;
        $this->bag = $bag;
        $this->dependencies = $dependencies;
    }
    /**
     * @inheritdoc
     */
    public function hasDependencies(): bool
    {
        return !empty($this->dependencies);
    }
    /**
     * @inheritdoc
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * @param Subject $subject
     * @param array $dependencies
     */
    public function update(Subject $subject, array $dependencies = []): void
    {
        $generalParameters = $dependencies['general_parameters'];

        $imageWordGameType1 = new ImageWordGameTypeParameter([
            'word_level' => 1,
            'time_trial' => null,
            'repeat' => [
                RepeatType::fromValue('end_repeat_all'),
                RepeatType::fromValue('had_trouble_words_repeat'),
                RepeatType::fromValue('had_trouble_words_repeat_end'),
            ],
            'order' => 0,
        ]);

        $sentenceType = new SentenceGameTypeParameter([
            'word_level' => 1,
            'time_trial' => null,
            'repeat' => [
                RepeatType::fromValue('end_repeat_all'),
            ],
            'order' => 1,
        ]);

        $imageWordGameType2 = new ImageWordGameTypeParameter([
            'word_level' => 1,
            'time_trial' => TimeTrialType::fromValue(10),
            'repeat' => [
                RepeatType::fromValue('end_repeat_all'),
                RepeatType::fromValue('had_trouble_words_repeat'),
                RepeatType::fromValue('had_trouble_words_repeat_end'),
            ],
            'order' => 2,
        ]);

        $types = [
            $imageWordGameType2,
            $sentenceType,
            $imageWordGameType1,
        ];

        $this->addToMetadata($types);
    }
    /**
     * @return string
     */
    public function getMarker(): string
    {
        return 'order';
    }

    /**
     * @param iterable $types
     */
    private function addToMetadata(iterable $types)
    {
        foreach ($types as $type) {
            $this->metadata[] = $type;
        }
    }
}