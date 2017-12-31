<?php

namespace Library\Listener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface $logger
     */
    private $logger;
    /**
     * @var Router $router
     */
    private $router;
    /**
     * ExceptionSubscriber constructor.
     * @param LoggerInterface $logger
     * @param Router $router
     */
    public function __construct(
        Router $router,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->router = $router;
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

        if ($exception instanceof AccessDeniedException) {
            $response = new RedirectResponse($this->router->generate('armor_user_login'));

            $event->setResponse($response);

            return;
        }

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