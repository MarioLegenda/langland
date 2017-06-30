<?php

namespace TestLibrary;

use Faker\Factory;
use Goutte\Client;

class DependencyHandler
{
    /**
     * @var $baseUri
     */
    private $baseUri;
    /**
     * @var Factory $faker
     */
    private $faker;
    /**
     * @var Client $client
     */
    private $client;
    /**
     * DependencyHandler constructor.
     * @param string|null $baseUri
     */
    public function __construct(string $baseUri = null)
    {
        $this->baseUri = $baseUri;
    }
    /**
     * @return DependencyHandler
     */
    public function useFaker() : DependencyHandler
    {
        $this->faker = Factory::create();

        return $this;
    }
    /**
     * @return DependencyHandler
     */
    public function useClient() : DependencyHandler
    {
        $this->client = new Client();
        $this->client->followRedirects();

        return $this;
    }
    /**
     * @return object
     */
    public function getFaker()
    {
        return $this->faker;
    }
    /**
     * @return Client
     */
    public function getClient() : Client
    {
        return $this->client;
    }
    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }
}