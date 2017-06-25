<?php

namespace AppBundle\GameScenario\Decision;

use AppBundle\Entity\LearningUserGame;
use AppBundle\GameScenario\TimeLimit;

abstract class AbstractDecision implements DecisionInterface
{
    /**
     * @var TimeLimit $timeLimit
     */
    protected $timeLimit;
    /**
     * @var LearningUserGame $game
     */
    protected $game;
    /**
     * AbstractDecision constructor.
     * @param LearningUserGame $game
     */
    public function __construct(LearningUserGame $game)
    {
        if ($game->getGame()->getHasTimeLimit()) {
            $this->timeLimit = new TimeLimit($game->getGame());
        }

        $this->game = $game;
    }
}