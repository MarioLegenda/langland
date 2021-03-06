<?php

namespace AdminBundle\Controller;

use PublicApi\Infrastructure\Type\CourseType;
use TestLibrary\LanglandAdminTestCase;
use Faker\Factory;
use Symfony\Component\DomCrawler\Crawler;
use FilesystemIterator;

class CourseControllerTest extends LanglandAdminTestCase
{
    private $navText = 'Courses';
    private $dashboardRoute = 'http://33.33.33.10/admin/dashboard';
    private $createUri = 'http://33.33.33.10/admin/course/create';
    private $editUri = 'http://33.33.33.10/admin/course/update';

    public function testCreate()
    {
        $faker = Factory::create();

        $createCrawler = $this->client->click($this->doTestDashboard($this->dashboardRoute, $this->navText)->selectLink('Create')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($this->createUri, $this->client->getRequest()->getUri());

        $createCrawler = $this->client->click($this->doTestDashboard($this->dashboardRoute, 'Languages')->selectLink('Create')->link());

        $this->doTestCreateLanguage($createCrawler, 'English');

        $createCrawler = $this->client->click($this->doTestDashboard($this->dashboardRoute, $this->navText)->selectLink('Create')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($this->createUri, $this->client->getRequest()->getUri());

        $this->doTestFailedValidation($createCrawler, array(
            array(
                'name' => 'form[name]',
                'value' => 'French'
            ),
        ));

        $this->doTestFailedValidation($createCrawler, array(
            array(
                'name' => 'form[whatToLearn]',
                'value' => 'description'
            ),
        ));

        $this->doTestFailedValidation($createCrawler, array(
            array(
                'name' => 'form[name]',
                'value' => 'French',
            ),
            array(
                'name' => 'form[whatToLearn]',
                'value' => 'description',
            ),
        ));

        $form = $createCrawler->selectButton('Create')->form(array(
            'form[name]' => 'Course name',
            'form[whatToLearn]' => $faker->text(600),
            'form[courseOrder]' => 1,
            'form[type]' => (string) CourseType::fromValue('Beginner'),
        ));

        $form['form[language]']->select('1');

        $this->client->submit($form);

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $form = $createCrawler->selectButton('Create')->form(array(
            'form[name]' => $faker->text(600),
            'form[whatToLearn]' => 'Description',
        ));

        $form['form[language]']->select('1');

        $this->client->submit($form);

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $courses = array('Course 1', 'Course 2');

        foreach ($courses as $course) {
            $form = $createCrawler->selectButton('Create')->form(array(
                'form[name]' => $course,
                'form[whatToLearn]' => 'Description',
                'form[courseOrder]' => 1,
                'form[type]' => (string) CourseType::fromValue('Beginner'),
            ));

            $form['form[language]']->select('1');

            $this->client->submit($form);

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        }
    }

    public function testUpdate()
    {
        $faker = Factory::create();

        $this->client->request('GET', $this->editUri.'/25');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

        $courseList = $this->doTestList($this->dashboardRoute, $this->navText);

        $oldCourses = array('Course 1', 'Course 2');
        $newCourses = array('Course 3', 'Course 4');

        $count = 0;
        $courseList->each(function(Crawler $card) use (&$count, $faker, $oldCourses, $newCourses) {
            $editLink = $card->selectLink('Edit')->link();

            $editCrawler = $this->client->click($editLink);

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

            $form = $editCrawler->selectButton('Edit')->form(array(
                'form[name]' => $newCourses[$count],
                'form[whatToLearn]' => 'Description',
                'form[courseOrder]' => 1,
                'form[type]' => (string) CourseType::fromValue('Beginner'),
            ));

            $form['form[language]']->select('1');

            $this->client->submit($form);

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

            ++$count;
        });
    }

    public function testIndex()
    {
        $this->doTestIndex(
            $this->dashboardRoute,
            $this->navText,
            array('Course 3', 'Course 4')
        );
    }

    private function doTestCreateLanguage(Crawler $createCrawler, string $language)
    {
        $this->doTestSuccessValidation($createCrawler, array(
            array(
                'name' => 'form[name]',
                'value' => $language,
                'form[courseOrder]' => 1,
                'form[type]' => 'Beginner',
            ),
            array(
                'name' => 'form[listDescription]',
                'value' => 'Text',
                'form[courseOrder]' => 1,
                'form[type]' => (string) CourseType::fromValue('Beginner'),
            ),
            array(
                'name' => 'form[showOnPage]',
                'value' => true,
                'form[courseOrder]' => 1,
                'form[type]' => 'Beginner',
            ),
        ));
    }
}