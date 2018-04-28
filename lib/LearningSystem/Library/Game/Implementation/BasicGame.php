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
     * @var string $type
     */
    private $type;
    /**
     * @var ProvidedDataInterface $gameData
     */
    private $gameData;
    /**
     * BasicGame constructor.
     * @param string $name
     * @param string $type
     * @param ProvidedDataInterface $gameData
     */
    public function __construct(
        string $name,
        string $type,
        ProvidedDataInterface $gameData
    ) {
        $this->name = $name;
        $this->type = $type;
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
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}