<?php

namespace AdminBundle\Controller;

use TestLibrary\LanglandAdminTestCase;
use Faker\Factory;
use Symfony\Component\DomCrawler\Crawler;
use FilesystemIterator;

class WordControllerTest extends LanglandAdminTestCase
{
    private $navText = 'Words';
    private $dashboardRoute = '/admin/dashboard';
    private $createUri = 'http://33.33.33.10/admin/word/create';
    private $editUri = 'http://33.33.33.10/admin/word/edit';

    public function testCreate()
    {
        $faker = Factory::create();

        $createCrawler = $this->client->click($this->doTestDashboard($this->dashboardRoute, 'Languages')->selectLink('Create')->link());

        $this->doTestCreateLanguage($createCrawler, 'French');

        $createCrawler = $this->client->click($this->doTestDashboard($this->dashboardRoute, $this->navText)->selectLink('Create')->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($this->createUri, $this->client->getRequest()->getUri());

        $this->doTestFailedValidation($createCrawler, array(
            array(
                'name' => 'form[name]',
                'value' => $faker->word,
            ),
        ));

        $form = $createCrawler->selectButton('Create')->form();

        $form['form[language]']->select('1');

        $this->client->submit($form);

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $words = array('Word 1', 'Word 2');

        foreach ($words as $word) {
            $form = $createCrawler->selectButton('Create')->form();

            $form['form[image][imageFile]']->upload(__DIR__.'/testImages/fr.png');
            $form['form[language]']->select('1');
            $form['form[name]'] = $word;

            $this->client->submit($form);

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        }

        $editedFi = new FilesystemIterator(__DIR__.'/../../uploads/images', FilesystemIterator::SKIP_DOTS);

        $this->assertEquals(3, iterator_count($editedFi));

    }

    public function testEdit()
    {
        $faker = Factory::create();

        $this->client->request('GET', $this->editUri.'/25');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

        $wordList = $this->doTestList($this->dashboardRoute, $this->navText);

        $oldWords = array('Word 1', 'Word 2');
        $newWords = array('Word 3', 'Word 4');

        $count = 0;
        $wordList->each(function(Crawler $languageCard) use (&$count, $faker, $oldWords, $newWords) {
            $editLink = $languageCard->filter('.sub-base-action-link')->link();

            $editCrawler = $this->client->click($editLink);

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

            $form = $editCrawler->selectButton('Edit')->form();

            $form['form[image][imageFile]']->upload(__DIR__.'/testImages/fr.png');
            $form['form[language]']->select('1');
            $form['form[name]'] = $newWords[$count];

            $this->client->submit($form);

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

            ++$count;
        });

        $editedFi = new FilesystemIterator(__DIR__.'/../../uploads/images', FilesystemIterator::SKIP_DOTS);

        $this->assertEquals(3, iterator_count($editedFi));
    }

    public function testIndex()
    {
        $this->doTestIndex(
            $this->dashboardRoute,
            $this->navText,
            array('Word 3', 'Word 4')
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