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
     * @var DataDeciderInterface $initialDataDecider
     */
    private $initialDataDecider;
    /**
     * DataCollector constructor.
     * @param DataDeciderInterface $initialDataDecider
     */
    public function __construct(
        DataDeciderInterface $initialDataDecider
    ) {
        $this->initialDataDecider = $initialDataDecider;
    }
    /**
     * @inheritdoc
     */
    public function getCollectedData(): array
    {

    }
}