<?php

namespace AdminBundle;

use Symfony\Component\DomCrawler\Crawler;
use TestLibrary\LanglandAdminTestCase;

class LanguageControllerTest extends LanglandAdminTestCase
{
    public function testIndex()
    {
        $client = self::$handler->getClient();
        $baseUri = self::$handler->getBaseUri();

        $client->request('GET', $baseUri.'/admin/language');

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

        $nameSizeLanguage = array(
            'name' => $faker->sentence(20),
            'description' => 'description',
        );

        $form = $crawler->selectButton('Create')->form(array(
            'form[name]' => $nameSizeLanguage['name'],
            'form[listDescription]' => $nameSizeLanguage['description'],
        ));

        $client->submit($form);

        $this->assertEquals(400, $client->getResponse()->getStatus());

        $descriptionSizeLanguage = array(
            'name' => 'mile',
            'description' => $faker->sentence(50),
        );

        $form = $crawler->selectButton('Create')->form(array(
            'form[name]' => $descriptionSizeLanguage['name'],
            'form[listDescription]' => $descriptionSizeLanguage['description'],
        ));

        $client->submit($form);

        $this->assertEquals(400, $client->getResponse()->getStatus());

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

        $frenchLanguage = array(
            'name' => 'spanish',
            'description' => $faker->sentence(20),
        );

        $form = $crawler->selectButton('Create')->form(array(
            'form[name]' => $frenchLanguage['name'],
        ));

        $client->submit($form);

        $this->assertEquals(400, $client->getResponse()->getStatus());
    }

    public function testEdit()
    {
        $client = self::$handler->getClient();
        $baseUri = self::$handler->getBaseUri();
        $host = self::$handler->getHost();

        $crawler = $client->request('GET', $baseUri.'/admin/language');

        $languageList = $crawler->filter('.page-content')->filter('.card');

        $languages = array(
            array(
                'name' => 'bulgarian',
                'description' => 'bulgarian description',
            ),
            array(
                'name' => 'english',
                'description' => 'english description',
            ),
        );

        $index = 0;
        $languageList->each(function(Crawler $node) use ($client, $languages, &$index, $host) {
            $href = $node->filter('.sub-base-action-link')->attr('href');

            $editName = $languages[$index]['name'];
            $editDescription = $languages[$index]['description'];

            $crawler = $client->request('GET', $host.$href);

            $this->assertEquals(200, $client->getResponse()->getStatus());

            $currentImgSrc = $crawler->filter('img')->attr('src');

            $form = $crawler->selectButton('Edit')->form(array(
                'form[name]' => $editName,
                'form[listDescription]' => $editDescription,
            ));

            $form['form[image][imageFile]']->upload(__DIR__.'/testImages/fr.png');

            $crawler = $client->submit($form);

            $this->assertEquals(200, $client->getResponse()->getStatus());

            $name = $crawler->filter('#form_name')->attr('value');
            $description = $crawler->filter('#form_listDescription')->text();
            $editedImgSrc = $crawler->filter('img')->attr('src');

            $this->assertEquals($name, $editName);
            $this->assertEquals($description, $editDescription);
            $this->assertNotEquals($editedImgSrc, $currentImgSrc);

            ++$index;
        });

        $crawler = $client->request('GET', $baseUri.'/admin/language');

        $languageList = $crawler->filter('.page-content')->filter('.base-action-link');

        $languages = array('bulgarian', 'english');
        $languageList->each(function(Crawler $node) use ($languages) {
            $text = $node->text();

            $this->assertContains($text, $languages);
        });
    }

    public function testListing()
    {
        $client = self::$handler->getClient();
        $baseUri = self::$handler->getBaseUri();

        $crawler = $client->request('GET', $baseUri.'/admin/language');

        $this->assertEquals(200, $client->getResponse()->getStatus());

        $languageList = $crawler->filter('.page-content')->filter('.base-action-link');

        $this->assertEquals(
            2,
            $languageList->count()
        );

        $languages = array('english', 'bulgarian');

        $crawler = $client->request('GET', $baseUri.'/admin/language');

        $languageList = $crawler->filter('.page-content')->filter('.base-action-link');

        $languageList->each(function(Crawler $node) use ($languages) {
            $text = $node->text();

            $this->assertContains($text, $languages);
        });
    }
}