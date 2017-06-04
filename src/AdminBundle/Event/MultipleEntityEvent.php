<?php

namespace AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class MultipleEntityEvent extends Event
{
    /**
     * @var array $entities
     */
    private $entities = array();
    /**
     * MultipleEntityEvent constructor.
     * @param array $entities
     */
    public function __construct(array $entities)
    {
        foreach ($entities as $entity) {
            if (!is_object($entity)) {
                throw new \RuntimeException(
                    sprintf('Custom %s exception. Every entity in an array has to be an object', MultipleEntityEvent::class)
                );
            }
        }

        $this->entities = $entities;
    }
    /**
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
    }
}