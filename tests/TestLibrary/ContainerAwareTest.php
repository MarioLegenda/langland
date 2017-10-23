<?php

namespace TestLibrary;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerAwareTest extends WebTestCase
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;

    public function setUp()
    {
        parent::setUp();

        $this->container = static::createClient()->getContainer();
    }
}