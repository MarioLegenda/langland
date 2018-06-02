<?php

namespace Library\Infrastructure\Logic;

interface DomainCommunicatorInterface
{
    /**
     * @return object
     */
    public function getForeignDomainModel(): object;
    /**
     * @return object
     */
    public function getDomainModel(): object;
}