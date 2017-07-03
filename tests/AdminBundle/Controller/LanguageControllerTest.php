<?php

namespace AdminBundle\Controller;

use Faker\Factory;
use Symfony\Component\DomCrawler\Crawler;
use TestLibrary\LanglandAdminTestCase;
use FilesystemIterator;

class LanguageControllerTest extends LanglandAdminTestCase
{
    private $navText = 'Languages';
    private $dashboardRoute = '/admin/dashboard';
    private $createUri = 'http://33.33.33.10/admin/language/create';
    private $editUri = 'http://33.33.33.10/admin/language/edit';

    public function testCreate()
    {
        $faker = Factory::create();

        $createCrawler = $this->client->click($this->doTestDashboard($this->dashboardRoute, $this->navText)->selectLink('Create')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($this->createUri, $this->client->getRequest()->getUri());


        $this->doTestFailedValidation($createCrawler, array(
            array(
                'name' => 'form[name]',
                'value' => 'French'
            )
        ));

        $this->doTestFailedValidation($createCrawler, array(
            array(
                'name' => 'form[listDescription]',
                'value' => 'French'
            ),
        ));

        $this->doTestFailedValidation($createCrawler, array(
            array(
                'name' => 'form[name]',
                'value' => 'French',
            ),
            array(
                'name' => 'form[listDescription]',
                'value' => $faker->sentence(256),
            ),
        ));

        $this->doTestFailedValidation($createCrawler, array(
            array(
                'name' => 'form[name]',
                'value' => $faker->sentence(51),
            ),
            array(
                'name' => 'form[listDescription]',
                'value' => $faker->sentence(50),
            ),
        ));

        $languages = array('French', 'Spanish');

        $count = 0;
        foreach ($languages as $language) {
            $this->doTestSuccessValidation($createCrawler, array(
                array(
                    'name' => 'form[name]',
                    'value' => $language,
                ),
                array(
                    'name' => 'form[listDescription]',
                    'value' => $faker->text(255),
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

            ++$count;

            $this->assertEquals($count, iterator_count($fi));
        }
    }

    public function testEdit()
    {
        $faker = Factory::create();

        $this->client->request('GET', $this->editUri.'/25');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

        $languageList = $this->doTestList($this->dashboardRoute, $this->navText);

        $oldLanguages = array('French', 'Spanish');
        $newLanguages = array('German', 'English');

        $count = 0;
        $languageList->each(function(Crawler $languageCard) use (&$count, $faker, $oldLanguages, $newLanguages) {
            $editLink = $languageCard->filter('.sub-base-action-link')->link();

            $editCrawler = $this->client->click($editLink);

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
            $this->assertEquals($this->editUri.'/'.($count+1), $this->client->getRequest()->getUri());

            $this->doTestSuccessValidation($editCrawler, array(
                array(
                    'name' => 'form[name]',
                    'value' => $newLanguages[$count],
                ),
                array(
                    'name' => 'form[listDescription]',
                    'value' => $faker->text(255),
                ),
                array(
                    'name' => 'form[image][imageFile]',
                    'value' => __DIR__.'/testImages/fr.png',
                ),
                array(
                    'name' => 'form[showOnPage]',
                    'value' => true,
                ),
            ), 'Edit');

            ++$count;
        });

        $editedFi = new FilesystemIterator(__DIR__.'/../../uploads/images', FilesystemIterator::SKIP_DOTS);

        $this->assertEquals($count, iterator_count($editedFi));
    }

    public function testIndex()
    {
        $this->doTestIndex(
            $this->dashboardRoute,
            $this->navText,
            array('German', 'English')
        );
    }
}