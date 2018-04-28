<?php

namespace LearningSystem\Library\Worker;

use LearningSystem\Infrastructure\Type\GameType\BasicGameType;
use LearningSystem\Library\Game\Implementation\BasicGame;
use LearningSystem\Library\Game\Implementation\GameInterface;
use LearningSystem\Library\DataDeciderInterface;

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
     * @param int $learningMetadataId
     * @return GameInterface
     */
    public function createGame(int $learningMetadataId): GameInterface
    {
        return $this->doCreateGame($learningMetadataId);
    }
    /**
     * @param int $learningMetadataId
     * @return GameInterface
     */
    private function doCreateGame(int $learningMetadataId): GameInterface
    {
        $decidedData = $this->dataDecider->getData($learningMetadataId);

        $gameType = $decidedData['game_type'];
        $data = $decidedData['data'];

        if ((string) $gameType === BasicGameType::getType()) {
            return new BasicGame(
                BasicGameType::getName(),
                BasicGameType::getType(),
                $data
            );
        }
    }
}