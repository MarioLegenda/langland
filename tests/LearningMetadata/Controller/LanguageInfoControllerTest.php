<?php

namespace AdminBundle\Controller;

use TestLibrary\LanglandAdminTestCase;
use Faker\Factory;
use Symfony\Component\DomCrawler\Crawler;
use FilesystemIterator;

class LanguageInfoControllerTest extends LanglandAdminTestCase
{
    private $navText = 'Language infos';
    private $dashboardRoute = 'http://33.33.33.10/admin/dashboard';
    private $editUri = 'http://33.33.33.10/admin/language-info/update';

    public function testCreate()
    {
        $faker = Factory::create();

        $createCrawler = $this->client->click($this->doTestDashboard($this->dashboardRoute, 'Languages')->selectLink('Create')->link());

        $this->doTestCreateLanguage($createCrawler, array('French', 'Spanish'));

        $createCrawler = $this->client->click($this->doTestDashboard($this->dashboardRoute, $this->navText)->selectLink('Create')->link());

        $this->doTestFailedValidation($createCrawler, array(
            array(
                'name' => 'form[name]',
                'value' => 'Some name'
            )
        ));

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $form = $createCrawler->selectButton('Create')->form();

        $form['form[name]'] = $faker->text(500);
        $form['form[language]']->select('1');

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $form['form[name]'] = 'Some name';
        $form['form[language]']->select('1');

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());

        $languageInfos = array('Language info 1', 'Language info 2');

        $count = 0;
        foreach ($languageInfos as $info) {
            $form['form[name]'] = $info;
            $form['form[language]']->select((string) ($count + 1));

            $values = $form->getPhpValues();

            $values['form']['languageInfoTexts'][0]['name'] = $faker->word;
            $values['form']['languageInfoTexts'][0]['text'] = 'Description';

            $values['form']['languageInfoTexts'][1]['name'] = $faker->word;
            $values['form']['languageInfoTexts'][1]['text'] = 'Description';

            $this->client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

            ++$count;
        }
    }

    public function testUpdate()
    {
        $faker = Factory::create();

        $this->client->request('GET', $this->editUri.'/25');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

        $infoList = $this->doTestList($this->dashboardRoute, $this->navText);

        $oldInfos = array('Language info 1', 'Language info 2');
        $newInfos = array('Language info 3', 'Language info 4');

        $count = 0;
        $infoList->each(function(Crawler $card) use (&$count, $faker, $oldInfos, $newInfos) {
            $editLink = $card->selectLink('Edit')->link();

            $editCrawler = $this->client->click($editLink);

            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
            $this->assertEquals($this->editUri.'/'.($count+1), $this->client->getRequest()->getUri());

            $form = $editCrawler->selectButton('Edit')->form();

            $form['form[name]'] = $newInfos[$count];
            $form['form[language]']->select((string) ($count + 1));

            $values = $form->getPhpValues();

            $values['form']['languageInfoTexts'][0]['name'] = $faker->word;
            $values['form']['languageInfoTexts'][0]['text'] = 'Description';

            $values['form']['languageInfoTexts'][1]['name'] = $faker->word;
            $values['form']['languageInfoTexts'][1]['text'] = 'Description';

            $this->client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

            $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

            ++$count;
        });
    }

    public function testIndex()
    {
        $this->doTestIndex(
            $this->dashboardRoute,
            $this->navText,
            array('Language info 3', 'Language info 4')
        );
    }

    private function doTestCreateLanguage(Crawler $createCrawler, array $languages)
    {
        foreach ($languages as $language) {
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
                    'name' => 'form[showOnPage]',
                    'value' => true,
                ),
            ));
        }
    }
}