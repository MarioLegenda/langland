<?php

namespace AppBundle\GameScenario;

use AppBundle\Entity\LearningUserGame;
use AppBundle\GameScenario\Decision\DecisionInterface;
use AppBundle\GameScenario\Decision\FreestyleDecision;
use AppBundle\GameScenario\Decision\ImageMasterDecision;
use AppBundle\GameScenario\Decision\TimeTrialDecision;

class DecisionFactory
{
    /**
     * @var LearningUserGame $game
     */
    private $game;
    /**
     * DecisionFactory constructor.
     * @param LearningUserGame $game
     */
    public function __construct(LearningUserGame $game)
    {
        $this->game = $game;
    }
    /**
     * @return DecisionInterface
     */
    public function makeDecision() : DecisionInterface
    {
        $gameTypes = $this->game->getGame()->getGameTypes();

        if (count($gameTypes) === 1) {
            $gameType = array_keys($gameTypes)[0];

            switch ($gameType) {
                case 'timeTrial':
                    return new TimeTrialDecision($this->game);
                case 'imageMaster':
                    return new ImageMasterDecision($this->game);
                case 'freestyle':
                    return new FreestyleDecision($this->game);
            }
        }
    }
}