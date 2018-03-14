<?php

namespace LearningSystem\Library\Game\Implementation;

use LearningSystem\Library\ProvidedDataInterface;

class BasicGame implements GameInterface
{
    /**
     * @var string $name
     */
    private $name;
    /**
     * @var ProvidedDataInterface $gameData
     */
    private $gameData;
    /**
     * BasicGame constructor.
     * @param string $name
     * @param ProvidedDataInterface $gameData
     */
    public function __construct(
        string $name,
        ProvidedDataInterface $gameData
    ) {
        $this->name = $name;
        $this->gameData = $gameData;
    }
    /**
     * @inheritdoc
     */
    public function getGameData(): ProvidedDataInterface
    {
        return $this->gameData;
    }
    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }
}