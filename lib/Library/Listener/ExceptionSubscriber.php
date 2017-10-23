<?php

namespace Library\Listener;

use Library\Exception\Factory\ExceptionLoggingHandlerFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var ExceptionLoggingHandlerFactory $exceptionLoggingFactory
     */
    private $exceptionLoggingFactory;
    /**
     * ExceptionSubscriber constructor.
     * @param ExceptionLoggingHandlerFactory $exceptionLoggingHandlerFactory
     */
    public function __construct(
        ExceptionLoggingHandlerFactory $exceptionLoggingHandlerFactory
    ) {
        $this->exceptionLoggingFactory = $exceptionLoggingHandlerFactory;
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => array(
                array('logException', 1),
            ),
        );
    }
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function logException(GetResponseForExceptionEvent $event)
    {
        $this->exceptionLoggingFactory->handle($event->getException());
    }
}