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

        $this->client->request('GET', $host.'/langland/course/english/1');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

        $this->createLanguageInfos($assertingLanguages, $em);

        $this->client->request('POST', $host.'/langland/api/user/create', array(
            'languageId' => 1,
        ));

        $location = $this->client->getResponse()->headers->get('location');

        $this->assertEquals('/langland/course/english/1', $location);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', $host.$location);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}