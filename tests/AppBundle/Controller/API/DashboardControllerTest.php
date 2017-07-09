<?php

namespace AppBundle\Controller\API;

use TestLibrary\LanglandUserTestCase;

class DashboardControllerTest extends LanglandUserTestCase
{
    public function testDashboard()
    {
        $this->client->followRedirects();
        $host = $_ENV['host'];

        $this->client->request('POST', $host.'/langland');

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', $host.'/langland');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCoursePageDashboard()
    {
        $this->client->followRedirects();
        $host = $_ENV['host'];
        $em = $this->client->getContainer()->get('doctrine')->getManager();
        $assertingLanguages = array('english', 'spanish', 'italian');

        $this->createLanguages($assertingLanguages, $em);
    }
}