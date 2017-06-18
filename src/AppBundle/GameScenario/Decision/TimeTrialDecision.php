<?php

namespace AppBundle\GameScenario\Decision;

use AppBundle\Entity\LearningUserGame;

class TimeTrialDecision extends AbstractDecision implements DecisionInterface, \JsonSerializable
{
    public function __construct(LearningUserGame $game)
    {
        parent::__construct($game);


    }

    public function jsonSerialize()
    {

    }
}