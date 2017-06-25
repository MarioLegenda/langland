<?php

namespace AppBundle\GameScenario;

use AppBundle\Entity\LearningUserGame;
use AppBundle\GameScenario\Decision\DecisionInterface;
use AppBundle\GameScenario\Decision\SimpleDecision;

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
		$simpleDecision = new SimpleDecision($this->game);
    }
}
