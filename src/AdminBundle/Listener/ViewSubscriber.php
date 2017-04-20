<?php

namespace AdminBundle\Listener;

use API\SharedDataBundle\Repository\LanguageRepository;
use API\SharedDataBundle\Repository\Status;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Templating\EngineInterface;

class ViewSubscriber implements EventSubscriberInterface
{
    private $templating;
    private $repo;
    private $user;

    public function __construct(EngineInterface $templating, LanguageRepository $repo, TokenStorage $tokenStorage)
    {
        $this->templating = $templating;
        $this->repo = $repo;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::VIEW => array(
                array('processWorkingLanguage', 10),
            )
        );
    }

    public function processWorkingLanguage(GetResponseForControllerResultEvent $event)
    {
        $controllerResult = $event->getControllerResult();

        $resultResolver = $this->repo->findWorkingLanguageByUser(array(
            'user_id' => $this->user->getId(),
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            $data = array_merge($controllerResult['data'], array(
                'working_language' => null,
            ));

            $event->setResponse($this->templating->renderResponse($controllerResult['template'], $data));
        } else if ($resultResolver->getStatus() === Status::SUCCESS) {
            $data = array_merge($controllerResult['data'], array(
                'working_language' => $resultResolver->getData(),
            ));

            $event->setResponse($this->templating->renderResponse($controllerResult['template'], $data));
        }
    }
}