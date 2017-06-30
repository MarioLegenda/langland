<?php

namespace PublicBundle\Controller;

use TestLibrary\DependencyHandler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class PublicControllerTest extends WebTestCase
{
    private static $handler;

    public static function setUpBeforeClass()
    {
        $handler = new DependencyHandler($_ENV['baseUri']);

        $handler
            ->useClient();

        self::$handler = $handler;
    }

    public function testHomePage()
    {
        $client = self::$handler->getClient();
        $baseUri = self::$handler->getBaseUri();

        $client->request('GET', $baseUri.'/langland/web/app_test.php');

        $this->assertEquals(200, $client->getResponse()->getStatus());
    }
}