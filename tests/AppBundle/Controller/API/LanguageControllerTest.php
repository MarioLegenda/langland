<?php

namespace AppBundle\Controller;

use TestLibrary\LanglandUserTestCase;
use TestLibrary\Seed;

class LanguageControllerTest extends LanglandUserTestCase
{
    public function testViewable()
    {
        $this->client->followRedirects();
        $host = $_ENV['host'];
        $em = $this->client->getContainer()->get('doctrine')->getManager();
        $assertingLanguages = array('english', 'spanish', 'italian');

        $this->client->request('GET', $host.'/langland/api/languages/viewable');

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', $host.'/langland/api/languages/viewable');

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        $this->client->request('HEAD', $host.'/langland/api/languages/viewable');

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        $this->client->request('PUT', $host.'/langland/api/languages/viewable');

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        $this->client->request('DELETE', $host.'/langland/api/languages/viewable');

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        $this->createLanguages($assertingLanguages, $em);

        $this->client->request('GET', $host.'/langland/api/languages/viewable');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $resource = $this->client->getResponse()->getContent();

        $this->assertNotEmpty($resource);
        $this->assertInternalType('string', $resource);

        $data = json_decode($resource, true);

        $this->assertNotEmpty($data);
        $this->assertInternalType('array', $data);

        foreach ($data as $index => $language) {
            $this->assertEquals($index+1, $language['id']);
            $this->assertEquals($assertingLanguages[$index], $language['name']);
            $this->assertInternalType('string', $language['listDescription']);
            $this->assertNotEmpty($language['listDescription']);
        }
    }

    public function testStructured()
    {
        $this->client->followRedirects();
        $host = $_ENV['host'];
        Seed::inst()->reset();
        $em = $this->client->getContainer()->get('doctrine')->getManager();
        $assertingLanguages = array('english', 'spanish', 'italian');

        $this->client->request('GET', $host.'/langland/api/languages/structured');

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', $host.'/langland/api/languages/structured');

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        $this->client->request('HEAD', $host.'/langland/api/languages/structured');

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        $this->client->request('PUT', $host.'/langland/api/languages/structured');

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        $this->client->request('DELETE', $host.'/langland/api/languages/structured');

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        $this->createLanguages($assertingLanguages, $em);

        $this->client->request('POST', $host.'/langland/api/user/create', array(
            'languageId' => 1,
        ));

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $location = $this->client->getResponse()->headers->get('Location');

        $this->assertEquals('/langland/course/english/1', $location);

        $this->client->request('GET', $host.'/langland/api/languages/structured');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $resource = $this->client->getResponse()->getContent();

        $this->assertNotEmpty($resource);
        $this->assertInternalType('string', $resource);

        $data = json_decode($resource, true);

        $this->assertNotEmpty($data);
        $this->assertInternalType('array', $data);
    }
}