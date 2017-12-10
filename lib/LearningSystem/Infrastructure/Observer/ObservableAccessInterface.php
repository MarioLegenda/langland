<?php

namespace LearningSystem\Infrastructure\Observer;

interface ObservableAccessInterface
{
    /**
     * @param string $name
     * @return Observer
     */
    public function getObserver(string $name);

    /**
     * @param string $name
     * @return bool
     */
    public function hasObserver(string $name): bool;
}