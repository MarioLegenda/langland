<?php

namespace PublicApi\LearningSystem\Infrastructure\DataCollector;

use LearningSystem\Library\DataCollectorInterface;
use PublicApi\LearningSystem\Infrastructure\DataDecider\DataDeciderInterface;

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