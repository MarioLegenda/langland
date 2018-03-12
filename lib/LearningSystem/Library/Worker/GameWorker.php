<?php

namespace LearningSystem\Library\Worker;

use LearningSystem\Library\DataCollectorInterface;

class GameWorker
{
    /**
     * @var DataCollectorInterface $dataCollector
     */
    private $dataCollector;
    /**
     * Worker constructor.
     * @param DataCollectorInterface $dataCollector
     */
    public function __construct(
        DataCollectorInterface $dataCollector
    ) {
        $this->dataCollector = $dataCollector;
    }

    public function createGame()
    {
        $data = $this->dataCollector->getCollectedData();


    }
}