<?php

namespace AppBundle\GameScenario;

class TypeFactory
{
	private $game;

	public function __construct(LearningUserGame $game)
	{
		$this->game = $game;
	}

	
}
