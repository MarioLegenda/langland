<?php

namespace AppBundle\Controller\API;

use TestLibrary\LanglandUserTestCase;

class UserControllerTest extends LanglandUserTestCase
{
    public function testCreateLearningUser()
    {
        $this->client->followRedirects();
        $host = $_ENV['host'];
        $em = $this->client->getContainer()->get('doctrine')->getManager();
        $assertingLanguages = array('english', 'spanish', 'italian');

        $this->createLanguages($assertingLanguages, $em);

        $this->client->request('POST', $host.'/langland/api/user/create', array(
            'languageId' => 1,
        ));

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', $host.'/langland/api/languages/structured');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $resource = $this->client->getResponse()->getContent();

        $this->assertNotEmpty($resource);
        $this->assertInternalType('string', $resource);

        $data = json_decode($resource, true);

        $this->assertNotEmpty($data);
        $this->assertInternalType('array', $data);

        $this->assertArrayHasKey('signedLanguages', $data);
        $this->assertArrayHasKey('currentLanguage', $data);

        $signedLanguages = $data['signedLanguages'];

        foreach ($signedLanguages as $language) {
            $this->assertInternalType('integer', $language['id']);
            $this->assertInternalType('string', $language['name']);
        }

        $currentLanguage = $data['currentLanguage'];

        $this->assertInternalType('integer', $currentLanguage['id']);
        $this->assertInternalType('string', $currentLanguage['name']);
    }

    public function testFindLearningUser()
    {
        $this->client->followRedirects();
        $host = $_ENV['host'];
        $em = $this->client->getContainer()->get('doctrine')->getManager();
        $assertingLanguages = array('english', 'spanish', 'italian');

        $this->createLanguages($assertingLanguages, $em);

        $this->client->request('POST', $host.'/langland/api/user/create', array(
            'languageId' => 1,
        ));

        $location = $this->client->getResponse()->headers->get('location');

        $this->assertEquals('/langland/course/english/1', $location);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', $host.'/langland/api/languages/structured');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', $host.'/langland/api/user');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $resource = $this->client->getResponse()->getContent();

        $this->assertNotEmpty($resource);
        $this->assertInternalType('string', $resource);

        $data = json_decode($resource, true);

        $this->assertArrayHasKey('learningUserId', $data);
        $this->assertArrayHasKey('user', $data);

        $this->assertInternalType('integer', $data['learningUserId']);
        $this->assertInternalType('array', $data['user']);

        $user = $data['user'];

        $this->assertInternalType('string', $user['name']);
        $this->assertInternalType('string', $user['lastname']);
        $this->assertInternalType('string', $user['username']);
    }

    public function testLoggedInUser()
    {
        $this->client->followRedirects();
        $host = $_ENV['host'];

        $this->client->request('GET', $host.'/langland/api/user/logged-in-user');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $resource = $this->client->getResponse()->getContent();

        $this->assertNotEmpty($resource);
        $this->assertInternalType('string', $resource);

        $data = json_decode($resource, true);

        $this->assertInternalType('string', $data['name']);
        $this->assertInternalType('string', $data['lastname']);
        $this->assertInternalType('string', $data['username']);
    }
}