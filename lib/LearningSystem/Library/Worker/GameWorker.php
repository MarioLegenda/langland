<?php

namespace LearningSystem\Library\Worker;

use LearningSystem\Infrastructure\Type\GameType\BasicGameType;
use LearningSystem\Infrastructure\Type\TypeInterface;
use LearningSystem\Library\Game\Implementation\BasicGame;
use LearningSystem\Library\Game\Implementation\GameInterface;
use PublicApi\LearningSystem\DataDecider\DataDeciderInterface;

class GameWorker
{
    /**
     * @var DataDeciderInterface $dataDecider
     */
    private $dataDecider;
    /**
     * Worker constructor.
     * @param DataDeciderInterface $dataDecider
     */
    public function __construct(
        DataDeciderInterface $dataDecider
    ) {
        $this->dataDecider = $dataDecider;
    }
    /**
     * @return GameInterface
     */
    public function createGame(): GameInterface
    {
        return $this->doCreateGame();
    }
    /**
     * @return GameInterface
     */
    private function doCreateGame(): GameInterface
    {
        $decidedData = $this->dataDecider->getData();

        $gameType = $decidedData['game_type'];
        $data = $decidedData['data'];

        if ((string) $gameType === BasicGameType::getName()) {
            return new BasicGame(BasicGameType::getName(), $data);
        }
    }
}