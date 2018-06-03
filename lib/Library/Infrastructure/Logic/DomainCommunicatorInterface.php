<?php

namespace Library\Infrastructure\Logic;

interface DomainCommunicatorInterface
{
    /**
     * @return object
     */
    public function getDomainModel(): object;
}