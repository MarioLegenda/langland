<?php

namespace AdminBundle;

use Goutte\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LanguageControllerTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = new Client();
        $this->login();

        exec('/usr/bin/php /var/www/bin/console do:da:dr --force');
        exec('/usr/bin/php /var/www/bin/console do:da:cr');
        exec('/usr/bin/php /var/www/bin/console do:sc:up --force');
        exec('/usr/bin/php /var/www/bin/console langland:user');
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', 'http://33.33.33.10/web/app_dev.php/admin/language');

        $link = $crawler->selectLink('Create')->link();

        $crawler = $this->client->click($link);

        $languages = array('spanish', 'french', 'bulgarian');

        foreach ($languages as $language) {
            $form = $crawler->selectButton('Create')->form(array(
                'form[name]' => $language,
            ));

            $this->client->submit($form);
        }
    }

    public function testListing()
    {
        $crawler = $this->client->request('GET', 'http://33.33.33.10/web/app_dev.php/admin/language');

        $languageList = $crawler->filter('.page-content')->filter('.base-action-link');

        $validLanguages = array('spanish', 'french', 'bulgarian');
        foreach ($languageList as $item) {
            $this->assertContains($item->nodeValue, $validLanguages, 'Dashboard does not contain one of previously created languages');
        }
    }

    public function testEdit()
    {
        $crawler = $this->client->request('GET', 'http://33.33.33.10/web/app_dev.php/admin/language');

        $languageList = $crawler->filter('.page-content')->filter('.base-action-link');

        $this->client->click($languageList->link('Edit'));
    }

    private function login()
    {
        $crawler = $this->client->request('GET', 'http://33.33.33.10/web/app_dev.php/admin/login');

        $button = $crawler->selectButton('SIGN IN');

        $form = $button->form(array(
            '_username' => 'root',
            '_password' => 'root',
        ));

        $this->client->submit($form);
    }
}