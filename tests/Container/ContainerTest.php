<?php

namespace Tests\Container;

use TestLibrary\ContainerAwareTest;

class ContainerTest extends ContainerAwareTest
{
    /**
     * @var array $businessImplementation
     */
    private $businessImplementations = [];
    /**
     * @var array $layers
     */
    private $layers = [];
    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->layers = [
            'learning_metadata',
            'public_api',
        ];

        $this->businessImplementations = [
            'language',
            'category',
            'word',
            'language_info',
            'sound',
            'course',
            'lesson',
            'basic_word_game',
        ];
    }

    public function test_repositories()
    {
        foreach ($this->layers as $layer) {
            $root = sprintf('langland.%s.repository', $layer);


            foreach ($this->businessImplementations as $implementation) {
                $this->assertService($root, $implementation);
            }
        }
    }

    public function test_business_implementation_objects()
    {
        foreach ($this->layers as $layer) {
            $root = sprintf('langland.%s.business.implementation.', $layer);

            foreach ($this->businessImplementations as $implementation) {
                $this->assertService($root, $implementation);
            }
        }
    }

    public function test_controller_objects()
    {
        foreach ($this->layers as $layer) {
            $root = sprintf('langland.%s.controller.', $layer);

            foreach ($this->businessImplementations as $implementation) {
                $this->assertService($root, $implementation);
            }
        }
    }
    /**
     * @param string $root
     * @param string $implementation
     */
    private function assertService(string $root, string $implementation)
    {
        $serviceId = sprintf($root.'%s', $implementation);

        if ($this->container->has($serviceId)) {
            $service = $this->container->get($serviceId);

            static::assertInternalType('object', $service);
        }
    }
}