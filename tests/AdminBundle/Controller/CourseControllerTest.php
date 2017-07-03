<?php

namespace AdminBundle\Controller;

use TestLibrary\LanglandAdminTestCase;
use Faker\Factory;
use Symfony\Component\DomCrawler\Crawler;
use FilesystemIterator;

class CourseControllerTest extends LanglandAdminTestCase
{
    private $navText = 'Courses';
    private $dashboardRoute = '/admin/dashboard';
    private $createUri = 'http://33.33.33.10/admin/course/create';
    private $editUri = 'http://33.33.33.10/admin/course/edit';

    public function testCreate()
    {
        $faker = Factory::create();

        $createCrawler = $this->client->click($this->doTestDashboard($this->dashboardRoute, $this->navText)->selectLink('Create')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($this->createUri, $this->client->getRequest()->getUri());

        $noData = $createCrawler->filter('.no-data-message');

        $this->assertEquals(1, count($noData));

        $createCrawler = $this->client->click($this->doTestDashboard($this->dashboardRoute, 'Languages')->selectLink('Create')->link());

        $this->doTestCreateLanguage($createCrawler, 'English');

        $createCrawler = $this->client->click($this->doTestDashboard($this->dashboardRoute, $this->navText)->selectLink('Create')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($this->createUri, $this->client->getRequest()->getUri());

        $noData = $createCrawler->filter('.no-data-message');

        $this->assertEquals(0, count($noData));

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
                'value' => 'French'
            ),
            array(
                'name' => 'form[whatToLearn]',
                'value' => 'description'
            ),
        ));

        $form = $createCrawler->selectButton('Create')->form(array(
            'form[name]' => 'Course name',
            'form[whatToLearn]' => $faker->text(600),
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
            ));

            $form['form[language]']->select('1');
            $form['form[initialCourse]']->tick();

            $this->client->submit($form);

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        }
    }

    public function testEdit()
    {
        $faker = Factory::create();

        $this->client->request('GET', $this->editUri.'/25');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

        $courseList = $this->doTestList($this->dashboardRoute, $this->navText);

        $oldCourses = array('Course 1', 'Course 2');
        $newCourses = array('Course 3', 'Course 4');

        $count = 0;
        $courseList->each(function(Crawler $languageCard) use (&$count, $faker, $oldCourses, $newCourses) {
            $editLink = $languageCard->filter('.sub-base-action-link')->link();

            $editCrawler = $this->client->click($editLink);

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

            $form = $editCrawler->selectButton('Edit')->form(array(
                'form[name]' => $newCourses[$count],
                'form[whatToLearn]' => 'Description',
            ));

            $form['form[language]']->select('1');
            $form['form[initialCourse]']->tick();

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
            array('Course 3 | English', 'Course 4 | English')
        );
    }

    private function doTestCreateLanguage(Crawler $createCrawler, string $language)
    {
        $this->doTestSuccessValidation($createCrawler, array(
            array(
                'name' => 'form[name]',
                'value' => $language,
            ),
            array(
                'name' => 'form[listDescription]',
                'value' => 'Text',
            ),
            array(
                'name' => 'form[image][imageFile]',
                'value' => __DIR__.'/testImages/fr.png',
            ),
            array(
                'name' => 'form[showOnPage]',
                'value' => true,
            ),
        ));

        $fi = new FilesystemIterator(__DIR__.'/../../uploads/images', FilesystemIterator::SKIP_DOTS);

        $this->assertEquals(1, iterator_count($fi));
    }
}