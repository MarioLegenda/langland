<?php

namespace AppBundle\GameScenario;

use AppBundle\Entity\LearningUserGame;

class TypeFactory
{
	private $game;

	public function __construct(LearningUserGame $game)
	{
		$this->game = $game;
	}

	
}
