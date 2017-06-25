<?php

namespace AppBundle\GameScenario;

class TimeLimit
{
    /**
     * @var int $minTime
     */
    private $minTime;
    /**
     * @var int $maxTime
     */
    private $maxTime;
    /**
     * @param object $game
     * TimeLimit constructor.
     */
    public function __construct($game)
    {
        $this->minTime = (int) $game->getMinTime();
        $this->maxTime = (int) $game->getMaxTime();
    }
    /**
     * @return int
     */
    public function getMinTime() : int
    {
        return $this->minTime;
    }
    /**
     * @return int
     */
    public function getMaxTime() : int
    {
        return $this->maxTime;
    }
}