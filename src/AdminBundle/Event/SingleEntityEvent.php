<?php

namespace AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class SingleEntityEvent extends Event
{
    /**
     * @var object $entity
     */
    protected $entity;
    /**
     * GenericEntityEvent constructor.
     * @param object $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }
    /**
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
    }
}