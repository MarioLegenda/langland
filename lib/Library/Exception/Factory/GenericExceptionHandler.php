<?php

namespace Library\Exception\Factory;

use Psr\Log\LoggerInterface;

class GenericExceptionHandler
{
    /**
     * @var \Exception $exception
     */
    protected $exception;
    /**
     * @var LoggerInterface $logger
     */
    protected $logger;
    /**
     * GenericExceptionHandler constructor.
     * @param \Exception $exception
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Exception $exception,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->exception = $exception;
    }
}