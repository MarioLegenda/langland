<?php

namespace AppBundle\Controller\API;

use TestLibrary\LanglandUserTestCase;

class CourseControllerTest extends LanglandUserTestCase
{
    public function testLanguageInfos()
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

        $this->client->request('POST', '/langland/api/courses/find-language-info');

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', '/langland/api/courses/find-language-info');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('name', $content);
        $this->assertInternalType('string', $content['name']);

        $this->assertArrayHasKey('languageInfoTexts', $content);
        $this->assertInternalType('array', $content['languageInfoTexts']);

        $this->assertEquals(5, count($content['languageInfoTexts']));
    }

    public function testLanguageCourses()
    {
        $this->client->followRedirects();
        $host = $_ENV['host'];
        $em = $this->client->getContainer()->get('doctrine')->getManager();
        $assertingLanguages = array('english', 'spanish', 'italian');

        $this->client->request('GET', $host.'/langland/course/english/1');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

        $languages = $this->createLanguageInfos($assertingLanguages, $em);
        $this->createLanguageCourses($languages, $em);

        $this->client->request('POST', $host.'/langland/api/user/create', array(
            'languageId' => 1,
        ));

        $location = $this->client->getResponse()->headers->get('location');

        $this->assertEquals('/langland/course/english/1', $location);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', '/langland/api/courses/language-courses');

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', '/langland/api/courses/language-courses');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertInternalType('array', $content);
        $this->assertNotEmpty($content);
        $this->assertEquals(6, count($content));

        $iterations = 0;
        foreach ($content as $course) {
            $this->assertArrayHasKey('id', $course);
            $this->assertInternalType('integer', $course['id']);

            $this->assertArrayHasKey('hasPassed', $course);
            $this->assertInternalType('boolean', $course['hasPassed']);
            $this->assertFalse($course['hasPassed']);

            $data = $course['course'];

            $this->assertArrayHasKey('name', $data);
            $this->assertInternalType('string', $data['name']);

            $this->assertArrayHasKey('whatToLearn', $data);
            $this->assertInternalType('string', $data['whatToLearn']);

            $this->assertArrayHasKey('initialCourse', $data);
            $this->assertInternalType('boolean', $data['initialCourse']);

            ($iterations === 0) ? $this->assertTrue($data['initialCourse']) : $this->assertFalse($data['initialCourse']);

            $this->assertArrayHasKey('courseUrl', $data);
            $this->assertInternalType('string', $data['courseUrl']);

            ++$iterations;
        }
    }

    public function testCourseFlow()
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

        $this->client->request('POST', $host.'/langland/api/courses/is-info-looked');

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', $host.'/langland/api/courses/mark-info-looked');

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());



        $this->client->request('GET', $host.'/langland/api/courses/is-info-looked');

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', $host.'/langland/api/courses/mark-info-looked');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', $host.'/langland/api/courses/is-info-looked');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', $host.$location);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}