<?php

namespace TestLibrary\Unit;

use Doctrine\DBAL\ConnectionException;
use Library\Exception\Factory\ExceptionLoggingHandlerFactory;
use TestLibrary\ContainerAwareTest;

class ExceptionHandlingTest extends ContainerAwareTest
{
    public function test_database_exception_handler()
    {
        try {
            throw ConnectionException::commitFailedRollbackOnly();
        } catch (\Exception $exception) {
            $exceptionHandler = $this->getExceptionHandlerFactory();

            $isHandled = $exceptionHandler->handle($exception);

            static::assertInstanceOf(ConnectionException::class, $isHandled);
        }
    }
    /**
     * @return ExceptionLoggingHandlerFactory
     */
    private function getExceptionHandlerFactory() : ExceptionLoggingHandlerFactory
    {
        return $this->container->get('library.exception.exception_handler');
    }
}