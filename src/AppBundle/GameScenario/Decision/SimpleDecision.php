<?php

namespace AppBundle\GameScenario\Decision;

use AppBundle\GameScenario\TimeLimit;

class SimpleDecision extends AbstractDecision implements DecisionInterface, \JsonSerializable
{
    /**
     * @return bool
     */
    public function hasTimeLimit() : bool
    {
        return ($this->timeLimit instanceof TimeLimit);
    }

    public function jsonSerialize()
    {
        $json = array();

        if ($this->hasTimeLimit()) {
            $json['hasTimeLimit'] = true;
            $json['timeLimit'] = array(
                'min' => $this->timeLimit->getMinTime(),
                'max' => $this->timeLimit->getMaxTime(),
            );
        }

        $json['gameName'] = $this->game->getGame()->getName();
    }
}
