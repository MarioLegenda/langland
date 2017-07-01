<?php

namespace AdminBundle\Controller;

use TestLibrary\LanglandAdminTestCase;
use Symfony\Component\DomCrawler\Crawler;

class CategoryControllerTest extends LanglandAdminTestCase
{
    public function testIndex()
    {
        $client = self::$handler->getClient();
        $baseUri = self::$handler->getBaseUri();

        $client->request('GET', $baseUri.'/admin/category');

        $this->assertEquals(200, $client->getResponse()->getStatus());
    }

    public function testCreate()
    {
        $client = self::$handler->getClient();
        $faker = self::$handler->getFaker();
        $baseUri = self::$handler->getBaseUri();

        $crawler = $client->request('GET', $baseUri.'/admin/category');

        $this->assertEquals(200, $client->getResponse()->getStatus());

        $link = $crawler->selectLink('Create')->link();

        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatus());

        $categorySize = array(
            'name' => $faker->sentence(20),
        );

        $form = $crawler->selectButton('Create')->form(array(
            'form[name]' => $categorySize['name'],
        ));

        $client->submit($form);

        $this->assertEquals(400, $client->getResponse()->getStatus());

        $categories = array('Nature', 'Soul');

        foreach ($categories as $category) {
            $form = $crawler->selectButton('Create')->form(array(
                'form[name]' => $category,
            ));

            $client->submit($form);

            $this->assertEquals(200, $client->getResponse()->getStatus());
        }
    }

    public function testEdit()
    {
        $client = self::$handler->getClient();
        $faker = self::$handler->getFaker();
        $baseUri = self::$handler->getBaseUri();
        $host = self::$handler->getHost();

        $crawler = $client->request('GET', $baseUri.'/admin/category');

        $this->assertEquals(200, $client->getResponse()->getStatus());

        $categoryList = $crawler->filter('.page-content')->filter('.card');

        $categories = array('Body', 'Love');

        $index = 0;
        $categoryList->each(function(Crawler $node) use ($client, $categories, &$index, $host, $faker) {
            $href = $node->filter('.sub-base-action-link')->attr('href');

            $editName = $categories[$index];

            $crawler = $client->request('GET', $host.$href);

            $this->assertEquals(200, $client->getResponse()->getStatus(), 'Something went wrong with uri '.$href);

            $categorySize = array(
                'name' => $faker->sentence(20),
            );

            $form = $crawler->selectButton('Edit')->form(array(
                'form[name]' => $categorySize['name'],
            ));

            $client->submit($form);

            $this->assertEquals(400, $client->getResponse()->getStatus());

            $form = $crawler->selectButton('Edit')->form(array(
                'form[name]' => $editName,
            ));

            $crawler = $client->submit($form);

            $this->assertEquals(200, $client->getResponse()->getStatus());

            $name = $crawler->filter('#form_name')->attr('value');

            $this->assertEquals($name, $editName);

            ++$index;
        });
    }

    public function testListing()
    {
        $client = self::$handler->getClient();
        $baseUri = self::$handler->getBaseUri();

        $crawler = $client->request('GET', $baseUri.'/admin/category');

        $this->assertEquals(200, $client->getResponse()->getStatus());

        $categoryList = $crawler->filter('.page-content')->filter('.base-action-link');

        $this->assertEquals(
            2,
            $categoryList->count()
        );

        $categories = array('Love', 'Body');

        $categoryList->each(function(Crawler $node) use ($categories) {
            $text = $node->text();

            $this->assertContains($text, $categories);
        });
    }
}