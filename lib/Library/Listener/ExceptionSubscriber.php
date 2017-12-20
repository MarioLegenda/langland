<?php

namespace Library\Listener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * ExceptionSubscriber constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
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
     * @throws \Exception
     */
    public function logException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof HttpExceptionInterface) {
            $response = new Response();
            $response->setStatusCode($exception->getStatusCode());

            $event->setResponse($response);

            return;
        }


        $message = sprintf(
            'Exception: %s; Message: %s; Stack trace: %s',
            get_class($exception),
            $exception->getMessage(),
            $exception->getTraceAsString()
        );

        $this->logger->alert($message);

        throw $exception;
    }
}