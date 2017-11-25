<?php

namespace Library\Exception;

class BaseStatusException extends \Exception
{
    /**
     * @var StatusInterface $status
     */
    protected $status;
}