<?php

namespace LearningSystem\Library\Game\Implementation;

use LearningSystem\Library\ProvidedDataInterface;

interface GameInterface
{
    /**
     * @return string
     */
    public function getName(): string;
    /**
     * @return ProvidedDataInterface
     */
    public function getGameData(): ProvidedDataInterface;
}