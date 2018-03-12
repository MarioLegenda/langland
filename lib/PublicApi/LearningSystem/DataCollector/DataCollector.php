<?php

namespace PublicApi\LearningSystem\DataCollector;

use LearningSystem\Library\DataCollectorInterface;
use LearningSystem\Library\Infrastructure\SystemHeadCollection;
use PublicApi\Infrastructure\Repository\WordRepository;
use PublicApi\LearningSystem\DataDecider\DataDeciderInterface;
use PublicApi\LearningSystem\DataDecider\InitialDataDecider;
use PublicApi\LearningSystem\RuleResolver;
use PublicApi\Lesson\Repository\LessonRepository;

class DataCollector implements DataCollectorInterface
{
    /**
     * @var LessonRepository $lessonRepository
     */
    private $lessonRepository;
    /**
     * @var WordRepository $wordRepository
     */
    private $wordRepository;
    /**
     * @var DataDeciderInterface $initialDataDecider
     */
    private $initialDataDecider;
    /**
     * DataCollector constructor.
     * @param LessonRepository $lessonRepository
     * @param WordRepository $wordRepository
     * @param DataDeciderInterface $initialDataDecider
     */
    public function __construct(
        LessonRepository $lessonRepository,
        WordRepository $wordRepository,
        DataDeciderInterface $initialDataDecider
    ) {
        $this->lessonRepository = $lessonRepository;
        $this->wordRepository = $wordRepository;
        $this->initialDataDecider = $initialDataDecider;
    }
    /**
     * @inheritdoc
     */
    public function getCollectedData(): array
    {
        $data = $this->initialDataDecider->getData();
    }
}