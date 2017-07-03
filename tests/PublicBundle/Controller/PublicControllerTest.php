<?php

namespace PublicBundle\Controller;

use TestLibrary\LanglandAdminTestCase;

class PublicControllerTest extends LanglandAdminTestCase
{
    private $route = '/';

    public function testIndex()
    {
        $index = $this->clientGet($this->route);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $signIn = $this->client->click($index->selectLink('SIGN IN')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->click($signIn->filter('.app a:first-child')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $signUp = $this->client->click($index->selectLink('SIGN UP')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->click($signUp->filter('.app a:first-child')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}