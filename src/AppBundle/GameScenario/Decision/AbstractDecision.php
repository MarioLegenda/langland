<?php

namespace AppBundle\GameScenario\Decision;

use AppBundle\Entity\LearningUserGame;

abstract class AbstractDecision
{
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
        $this->game = $game;
    }
}