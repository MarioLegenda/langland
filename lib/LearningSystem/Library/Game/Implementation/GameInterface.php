<?php

namespace LearningSystem\Library\Game\Implementation;

use LearningSystem\Library\ProvidedDataInterface;

interface GameInterface
{
    /**
     * @return ProvidedDataInterface
     */
    public function getGameData(): ProvidedDataInterface;
}