<?php

namespace Library\Exception\Factory;

use Doctrine\DBAL\ConnectionException;
use Psr\Log\LoggerInterface;

class ExceptionLoggingHandlerFactory
{
    /**
     * @var array $handlers
     */
    private $handlers = [];
    /**
     * ExceptionMessageFactory constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->handlers[ConnectionException::class] = function(ConnectionException $exception) use ($logger) {
            return new DatabaseExceptionHandler($exception, $logger);
        };
    }
    /**
     * @param \Exception $exception
     * @return \Exception|null
     */
    public function handle(\Exception $exception)
    {
        $class = get_class($exception);
        if (!$this->hasHandler($class)) {
            return null;
        }

        return $this->getHandler($class, $exception)->handle();
    }
    /**
     * @param \string $class
     * @return bool
     */
    public function hasHandler(string $class) : bool
    {
        return array_key_exists($class, $this->handlers);
    }
    /**
     * @param string $class
     * @param \Exception
     * @return ExceptionHandlerInterface
     */
    private function getHandler(string $class, \Exception $exception): ExceptionHandlerInterface
    {
        return $this->handlers[$class]->__invoke($exception);
    }
}