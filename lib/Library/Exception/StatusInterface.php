<?php

namespace Library\Exception;

interface StatusInterface
{
    public function getData(): ?array;
    public function getStatusCode(): int;
}