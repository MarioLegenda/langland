<?php

namespace Library\Exception\Factory;

use Doctrine\DBAL\ConnectionException;
use Psr\Log\LoggerInterface;

class DatabaseExceptionHandler extends GenericExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @void
     */
    public function handle()
    {
        $message = 'A Doctrine ConnectionException has been thrown with message: %s';
        $this->logger->critical(sprintf($message, $this->exception->getMessage()));

        return $this->exception;
    }
}