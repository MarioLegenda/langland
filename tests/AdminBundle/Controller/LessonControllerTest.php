<?php

namespace AdminBundle\Controller;

use Symfony\Component\DomCrawler\Crawler;
use TestLibrary\LanglandAdminTestCase;
use Faker\Factory;

class LessonControllerTest extends LanglandAdminTestCase
{
    private $navText = 'Lessons';
    private $dashboardRoute = '/admin/dashboard';

    public static function setUpBeforeClass()
    {
        exec('/usr/bin/php /var/www/bin/console langland:reset');
        exec('/usr/bin/php /var/www/bin/console langland:seed');
    }

    public function testClickPath()
    {
        $faker = Factory::create();

        $courseIndex = $this->doTestDashboard($this->dashboardRoute, 'Courses');

        $cards = $courseIndex->filter('.page-content .card');

        $this->assertNotEquals(0, count($cards));

        $cards->each(function(Crawler $crawler) {
            $link = $crawler->selectLink('Manage')->link();

            $this->client->click($link);

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        });

        $courseDashboard = $this->client->click($cards->first()->selectLink('Manage')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreate()
    {
        $faker = Factory::create();

        $courseIndex = $this->doTestDashboard($this->dashboardRoute, 'Courses');

        $cards = $courseIndex->filter('.page-content .card');

        $this->assertNotEquals(0, count($cards));

        $this->client->click($cards->first()->selectLink('Manage')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $uri = $this->client->getRequest()->getUri();

        $createCrawler = $this->client->click($this->doTestDashboard($uri, $this->navText)->selectLink('Create')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->doTestFailedValidation($createCrawler, array(
            array(
                'name' => 'form[name]',
                'value' => ''
            )
        ));

        $this->doTestFailedValidation($createCrawler, array(
            array(
                'name' => 'form[name]',
                'value' => 'Some lesson'
            )
        ));

        $lessons = array('Lesson 1', 'Lesson 2');
        $form = $createCrawler->selectButton('Create')->form();

        foreach ($lessons as $lesson) {
            $form['form[name]'] = $lesson;
            $form['form[description]'] = 'Description';

            $values = $form->getPhpValues();

            $values['form']['lessonTexts'][0]['name'] = $faker->word;
            $values['form']['lessonTexts'][0]['text'] = 'Text';

            $values['form']['lessonTexts'][1]['name'] = $faker->word;
            $values['form']['lessonTexts'][1]['text'] = 'Text';

            $this->client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        }
    }

    public function testFindLessonsByCourse()
    {
        $courseId = 1234;
        $host = $_ENV['host'];
        $uri = '/admin/course/manage/'.$courseId.'/lesson/lessons-by-course';

        $this->client->request('POST', $host.$uri);

        $this->assertEquals(405, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', $uri);

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());

        $courseId = 10;
        $uri = '/admin/course/manage/'.$courseId.'/lesson/lessons-by-course';

        $this->client->request('GET', $uri);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertNotEmpty($content);
        $this->assertInternalType('array', $content);
        $this->assertEquals(10, count($content));

        $count = 0;
        foreach ($content as $data) {
            $this->assertArrayHasKey('id', $data);
            $this->assertInternalType('integer', $data['id']);

            $this->assertArrayHasKey('name', $data);
            $this->assertInternalType('string', $data['name']);

            $this->assertArrayHasKey('description', $data);
            $this->assertInternalType('string', $data['description']);

            $this->assertArrayHasKey('lessonUrl', $data);
            $this->assertInternalType('string', $data['lessonUrl']);

            $this->assertArrayHasKey('isInitialLesson', $data);
            $this->assertInternalType('boolean', $data['isInitialLesson']);
            ($count === 0) ? $this->assertTrue($data['isInitialLesson']) : $this->assertFalse($data['isInitialLesson']);

            ++$count;
        }

        $this->client->request('GET', $uri.'?type=autocomplete');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertNotEmpty($content);
        $this->assertInternalType('array', $content);
        $this->assertEquals(10, count($content));

        foreach ($content as $data) {
            $this->assertArrayHasKey('id', $data);
            $this->assertInternalType('integer', $data['id']);

            $this->assertArrayHasKey('name', $data);
            $this->assertInternalType('string', $data['name']);
        }

        $this->client->request('GET', $uri.'?type=withText');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $content = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertNotEmpty($content);
        $this->assertInternalType('array', $content);
        $this->assertEquals(10, count($content));

        $count = 0;
        foreach ($content as $data) {
            $this->assertArrayHasKey('id', $data);
            $this->assertInternalType('integer', $data['id']);

            $this->assertArrayHasKey('name', $data);
            $this->assertInternalType('string', $data['name']);

            $this->assertArrayHasKey('description', $data);
            $this->assertInternalType('string', $data['description']);

            $this->assertArrayHasKey('lessonUrl', $data);
            $this->assertInternalType('string', $data['lessonUrl']);

            $this->assertArrayHasKey('isInitialLesson', $data);
            $this->assertInternalType('boolean', $data['isInitialLesson']);
            ($count === 0) ? $this->assertTrue($data['isInitialLesson']) : $this->assertFalse($data['isInitialLesson']);

            $this->assertArrayHasKey('lessonText', $data);
            $this->assertInternalType('array', $data['lessonText']);
            $this->assertEquals(5, count($data['lessonText']));

            $lessonText = $data['lessonText'];

            foreach ($lessonText as $text) {
                $this->assertArrayHasKey('name', $text);
                $this->assertInternalType('string', $text['name']);

                $this->assertArrayHasKey('text', $text);
                $this->assertInternalType('string', $text['text']);
            }

            ++$count;
        }
    }
}