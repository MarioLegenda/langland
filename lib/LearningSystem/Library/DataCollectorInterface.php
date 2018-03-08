<?php

namespace LearningSystem\Library;


interface DataCollectorInterface
{
    /**
     * @return array
     */
    public function getCollectedData(): array;
}