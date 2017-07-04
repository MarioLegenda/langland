<?php

namespace AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResponseCreatorTest extends WebTestCase
{
    public function testDetermineResponse()
    {
        static::bootKernel(array());

        $container = static::$kernel->getContainer();

        $responseCreator = $container->get('app_response_creator');

        $response = $responseCreator->createNoContentResponse();

        $this->assertEquals(204, $response->getStatusCode());

        $response = $responseCreator->createContentAvailableResponse(array());

        $this->assertEquals(200, $response->getStatusCode());

        $response = $responseCreator->determineResponse(array());

        $this->assertEquals(204, $response->getStatusCode());

        $response = $responseCreator->determineResponse(null);

        $this->assertEquals(204, $response->getStatusCode());

        $response = $responseCreator->determineResponse(array('some-content' => 'value'));

        $this->assertEquals(200, $response->getStatusCode());
    }
}