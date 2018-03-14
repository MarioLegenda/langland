<?php

namespace LearningSystem\Library\Game\Implementation;

use LearningSystem\Library\ProvidedDataInterface;

class BasicGame implements GameInterface
{
    /**
     * @var ProvidedDataInterface $gameData
     */
    private $gameData;
    /**
     * BasicGame constructor.
     * @param ProvidedDataInterface $gameData
     */
    public function __construct(ProvidedDataInterface $gameData)
    {
        $this->gameData = $gameData;
    }
    /**
     * @return ProvidedDataInterface
     */
    public function getGameData(): ProvidedDataInterface
    {
        return $this->gameData;
    }
}