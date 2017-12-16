<?php

namespace LearningSystem\Algorithm\Initial\Parameter;

use LearningSystem\Infrastructure\Observer\Observer;
use LearningSystem\Infrastructure\ParameterBagInterface;
use LearningSystem\Infrastructure\Observer\Subject;
use LearningSystem\Algorithm\Initial\Type\FreeTimeType;
use LearningSystem\Algorithm\Initial\Type\PersonType;
use LearningSystem\Infrastructure\Type\SwitchType;
use LearningSystem\Infrastructure\Type\TypeInterface;

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
            'word_number' => $this->calculateWordNumber(),
            'word_level' => $this->calculateWordLevel(),
        ];

        $this->isProcessed = true;
    }
    /**
     * @return int
     */
    private function calculateWordNumber(): int
    {
        $wordNumber = 15;

        $speakingLanguages = $this->bag->get('speaking_language')['parameter']['value'];
        $personType = PersonType::fromKey($this->bag->get('person_type')['parameter']['value']);
        $freeTime = FreeTimeType::fromKey($this->bag->get('free_time')['parameter']['value']);
        $challengeType = SwitchType::fromKey($this->bag->get('challenges')['parameter']['value']);
        $stressfulJob = SwitchType::fromKey($this->bag->get('stressful_job')['parameter']['value']);

        if ($speakingLanguages >= 1) {
            $wordNumber += 3;
        }

        $wordNumber = $this->calculatePersonTypeWordNumber($personType, $wordNumber);
        $wordNumber = $this->calculateFreeTimeWordNumber($freeTime, $wordNumber);
        $wordNumber = $this->calculateChallengeTypeWordNumber($challengeType, $wordNumber);

        if ($stressfulJob === 1) {
            if ($wordNumber >= 20) {
                $wordNumber -= 5;
            }
        }

        if ($wordNumber < 15) {
            $wordNumber = 15;
        }

        return $wordNumber;
    }
    /**
     * @return int
     */
    private function calculateWordLevel(): int
    {
        return 1;
    }
    /**
     * @param TypeInterface $personType
     * @param int $wordNumber
     * @throws \RuntimeException
     * @return int
     */
    private function calculatePersonTypeWordNumber(TypeInterface $personType, int $wordNumber): int
    {
        switch ($personType->getKey()) {
            case 0:
                return $wordNumber += 3;
            case 1:
                return $wordNumber -= 2;
        }

        throw new \RuntimeException('Initial general parameters word number could not be constructed from person type');
    }
    /**
     * @param TypeInterface $freeTime
     * @param int $wordNumber
     * @throws \RuntimeException
     * @return int
     */
    private function calculateFreeTimeWordNumber(TypeInterface $freeTime, int $wordNumber): int
    {
        switch ($freeTime->getKey()) {
            case 0:
                return $wordNumber -= 2;
            case 1:
                return $wordNumber;
            case 2:
                return $wordNumber += 3;
            case 3:
                return $wordNumber += 6;
        }

        throw new \RuntimeException('Initial general parameters word number could not be constructed from free time type');
    }
    /**
     * @param TypeInterface $challengeType
     * @param int $wordNumber
     * @throws \RuntimeException
     * @return int
     */
    public function calculateChallengeTypeWordNumber(TypeInterface $challengeType, int $wordNumber): int
    {
        switch ($challengeType->getKey()) {
            case 0:
                return $wordNumber -= 2;
            case 1:
                return $wordNumber += 2;
        }

        throw new \RuntimeException('Initial general parameters word number could not be constructed from challenge type');
    }
}