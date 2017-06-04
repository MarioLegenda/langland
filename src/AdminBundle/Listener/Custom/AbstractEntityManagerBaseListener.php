<?php

namespace AdminBundle\Listener\Custom;

use Doctrine\ORM\EntityManager;

abstract class AbstractEntityManagerBaseListener
{
    /**
     * @var EntityManager $em
     */
    protected $em;
    /**
     * PostPersistListener constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
}