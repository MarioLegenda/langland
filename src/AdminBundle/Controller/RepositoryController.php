<?php

namespace AdminBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RepositoryController extends Controller
{
    /**
     * @param string $repository
     * @return EntityRepository
     */
    protected function getRepository(string $repository) : EntityRepository
    {
        return $this->get('doctrine')->getRepository($repository);
    }

    /**
     * @param string $eventClass
     * @param $entity
     * @return void
     */
    protected function dispatchEvent(string $eventClass, $entity)
    {
        $eventDispatcher = $this->get('event_dispatcher');

        $event = new $eventClass($entity);

        $eventDispatcher->dispatch($event::NAME, $event);
    }
}