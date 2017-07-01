<?php

namespace AdminBundle\Controller;

use TestLibrary\LanglandAdminTestCase;
use Symfony\Component\DomCrawler\Crawler;

class LanguageInfoTest extends LanglandAdminTestCase
{
    public function testIndex()
    {
        $client = self::$handler->getClient();
        $baseUri = self::$handler->getBaseUri();

        $client->request('GET', $baseUri.'/admin/language-info');

        $this->assertEquals(200, $client->getResponse()->getStatus());
    }

    public function testCreate()
    {
        $client = self::$handler->getClient();
        $faker = self::$handler->getFaker();
        $baseUri = self::$handler->getBaseUri();

        $languages = array(
            array(
                'name' => 'french',
                'description' => $faker->sentence(20),
            ),
            array(
                'name' => 'spanish',
                'description' => $faker->sentence(20),
            ),
        );

        $crawler = $client->request('GET', $baseUri.'/admin/language');

        $this->assertEquals(200, $client->getResponse()->getStatus());

        $link = $crawler->selectLink('Create')->link();

        $crawler = $client->click($link);

        foreach ($languages as $singleLanguage) {
            $form = $crawler->selectButton('Create')->form(array(
                'form[name]' => $singleLanguage['name'],
            ));

            $client->submit($form);

            $this->assertEquals(400, $client->getResponse()->getStatus());

            $crawler = $client->request('GET', $baseUri.'/admin/language/create');

            $form = $crawler->selectButton('Create')->form();

            $form['form[name]'] = $singleLanguage['name'];
            $form['form[listDescription]'] = $singleLanguage['description'];
            $form['form[showOnPage]'] = true;
            $form['form[image][imageFile]']->upload( __DIR__.'/testImages/us.png');

            $crawler = $client->submit($form);

            $this->assertEquals(200, $client->getResponse()->getStatus());
        }

        $infos = array(
            array(
                'name' => 'French info',
                'language' => 1,
            ),
            array(
                'name' => 'Spanish info',
                'language' => 2,
            ),
        );

        foreach ($infos as $info) {
            $crawler = $client->request('GET', $baseUri.'/admin/language-info');

            $this->assertEquals(200, $client->getResponse()->getStatus());

            $link = $crawler->selectLink('Create')->link();

            $crawler = $client->click($link);

            $this->assertEquals(200, $client->getResponse()->getStatus());

            $form = $crawler->selectButton('Create')->form(array(
                'form[name]' => $info['name'],
            ));

            $client->submit($form);

            $this->assertEquals(400, $client->getResponse()->getStatus());

            $form['form[language]']->select($info['language']);

            $client->submit($form);

            $this->assertEquals(400, $client->getResponse()->getStatus());

            $values = $form->getPhpValues();

            $values['form']['languageInfoTexts'][0]['name'] = $faker->word;
            $values['form']['languageInfoTexts'][0]['text'] = $faker->sentence(20);

            $values['form']['languageInfoTexts'][1]['name'] = $faker->word;
            $values['form']['languageInfoTexts'][1]['text'] = $faker->sentence(20);

            $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

            $this->assertEquals(200, $client->getResponse()->getStatus());
        }

        $crawler = $client->request('GET', $baseUri.'/admin/language-info/create');

        $form = $crawler->selectButton('Create')->form(array(
            'form[name]' => 'French info',
            'form[language]' => '1',
        ));

        $client->submit($form);

        $this->assertEquals(400, $client->getResponse()->getStatus());
    }

    public function testEdit()
    {
        $client = self::$handler->getClient();
        $baseUri = self::$handler->getBaseUri();
        $host = self::$handler->getHost();
        $faker = self::$handler->getFaker();

        $crawler = $client->request('GET', $baseUri.'/admin/language-info');

        $this->assertEquals(200, $client->getResponse()->getStatus());

        $infoList = $crawler->filter('.page-content')->filter('.card');

        $this->assertEquals(
            2,
            $infoList->count()
        );

        $infos = array(
            array(
                'name' => 'German info',
            ),
            array(
                'name' => 'English info',
            ),
        );

        $index = 0;
        $infoList->each(function(Crawler $node) use ($client, $infos, &$index, $host, $faker) {
            $href = $node->filter('.sub-base-action-link')->attr('href');

            $editName = $infos[$index]['name'];

            $crawler = $client->request('GET', $host.$href);

            $this->assertEquals(200, $client->getResponse()->getStatus());

            $form = $crawler->selectButton('Edit')->form(array(
                'form[name]' => $editName,
            ));

            $values['form']['languageInfoTexts'][0]['name'] = $faker->word;
            $values['form']['languageInfoTexts'][0]['text'] = $faker->sentence(20);

            $values['form']['languageInfoTexts'][1]['name'] = $faker->word;
            $values['form']['languageInfoTexts'][1]['text'] = $faker->sentence(20);

            $crawler = $client->submit($form);

            $this->assertEquals(200, $client->getResponse()->getStatus());

            $editedForm = $crawler->selectButton('Edit')->form();

            $name = $editedForm['form[name]'];

            $this->assertNotEquals($name, $editName);

            ++$index;
        });
    }

    public function testListing()
    {
        $client = self::$handler->getClient();
        $baseUri = self::$handler->getBaseUri();

        $crawler = $client->request('GET', $baseUri.'/admin/language-info');

        $this->assertEquals(200, $client->getResponse()->getStatus());

        $languageList = $crawler->filter('.page-content')->filter('.base-action-link');

        $this->assertEquals(
            2,
            $languageList->count()
        );

        $infos = array('German info | french', 'English info | spanish');

        $crawler = $client->request('GET', $baseUri.'/admin/language-info');

        $languageList = $crawler->filter('.page-content')->filter('.base-action-link');

        $languageList->each(function(Crawler $node) use ($infos) {
            $text = $node->text();

            $this->assertContains($text, $infos);
        });
    }
}