<?php

namespace LearningSystem\Library\Worker;

use LearningSystem\Infrastructure\Type\GameType\BasicGameType;
use LearningSystem\Library\Game\Implementation\BasicGame;
use LearningSystem\Library\Game\Implementation\GameInterface;
use LearningSystem\Library\DataDeciderInterface;
use PublicApiBundle\Entity\LearningLesson;
use PublicApiBundle\Entity\LearningMetadata;

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
     * @param LearningLesson $learningLesson
     * @return GameInterface
     */
    public function createGame(LearningLesson $learningLesson): GameInterface
    {
        return $this->doCreateGame($learningLesson);
    }
    /**
     * @param LearningLesson $learningLesson
     * @return GameInterface
     */
    private function doCreateGame(LearningLesson $learningLesson): GameInterface
    {
        $decidedData = $this->dataDecider->getData($learningLesson);

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