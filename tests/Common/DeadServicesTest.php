<?php

namespace TestLibrary\Common;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DeadServicesTest extends WebTestCase
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    public function setUp()
    {
        $this->container = self::createClient()->getContainer();
    }
}