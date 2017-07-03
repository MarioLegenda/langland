<?php

namespace AdminBundle\Controller;

use Faker\Factory;
use function Symfony\Component\Validator\Tests\Constraints\choice_callback;
use TestLibrary\LanglandAdminTestCase;
use Symfony\Component\DomCrawler\Crawler;

class CategoryControllerTest extends LanglandAdminTestCase
{
    private $navText = 'Categories';
    private $dashboardRoute = '/admin/dashboard';
    private $createUri = 'http://33.33.33.10/admin/category/create';
    private $editUri = 'http://33.33.33.10/admin/category/edit';

    public function testCreate()
    {
        $faker = Factory::create();

        $createCrawler = $this->client->click($this->doTestDashboard($this->dashboardRoute, $this->navText)->selectLink('Create')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($this->createUri, $this->client->getRequest()->getUri());

        $this->doTestFailedValidation($createCrawler, array(
            array(
                'name' => 'form[name]',
                'value' => '',
            )
        ));

        $this->doTestFailedValidation($createCrawler, array(
            array(
                'name' => 'form[name]',
                'value' => $faker->text(256),
            )
        ));

        $categories = array('Nature', 'Love');

        foreach ($categories as $category) {
            $this->doTestSuccessValidation($createCrawler, array(
                array(
                    'name' => 'form[name]',
                    'value' => $category,
                ),
            ));
        }
    }

    public function testEdit()
    {
        $faker = Factory::create();

        $this->client->request('GET', $this->editUri.'/25');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

        $this->client->click($this->clientGet($this->dashboardRoute)->selectLink($this->navText)->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $categoryList = $this->doTestList($this->dashboardRoute, $this->navText);

        $oldCategories = array('Nature', 'Love');
        $newCategories = array('Soul', 'Body');

        $count = 0;
        $categoryList->each(function(Crawler $languageCard) use (&$count, $faker, $oldCategories, $newCategories) {
            $editLink = $languageCard->filter('.sub-base-action-link')->link();

            $editCrawler = $this->client->click($editLink);

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

            $this->doTestSuccessValidation($editCrawler, array(
                array(
                    'name' => 'form[name]',
                    'value' => $newCategories[$count],
                ),
            ), 'Edit');

            ++$count;
        });
    }

    public function testIndex()
    {
        $this->doTestIndex(
            $this->dashboardRoute,
            $this->navText,
            array('Soul', 'Body')
        );
    }
}