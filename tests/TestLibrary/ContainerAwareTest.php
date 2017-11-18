<?php

namespace TestLibrary;

use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerAwareTest extends WebTestCase
{
    /**
     * @var ContainerInterface $container
     */
    protected $container;
    /**
     * @var Generator $faker
     */
    protected $faker;

    public function setUp()
    {
        parent::setUp();

        $this->faker = Factory::create();

        $this->container = static::createClient()->getContainer();
    }
}