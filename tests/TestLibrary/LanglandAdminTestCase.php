<?php

namespace TestLibrary;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LanglandAdminTestCase extends WebTestCase
{
    protected static $handler;

    public static function setUpBeforeClass()
    {
        exec('/usr/bin/php /var/www/langland/bin/console langland:reset');

        $handler = new DependencyHandler($_ENV['baseUri'], $_ENV['host']);

        $handler
            ->useClient()
            ->useFaker();

        self::$handler = $handler;

        self::login();
    }

    public static function tearDownAfterClass()
    {
        exec('/usr/bin/php /var/www/langland/bin/console langland:reset');

        $dirs = array(
            realpath(__DIR__.'/../uploads/images'),
            realpath(__DIR__.'/../uploads/sounds'),
        );

        foreach ($dirs as $dir) {
            foreach (new \DirectoryIterator($dir) as $fileInfo) {
                if(!$fileInfo->isDot()) {
                    unlink($fileInfo->getPathname());
                }
            }
        }
    }

    protected static function login()
    {
        $client = self::$handler->getClient();
        $baseUri = self::$handler->getBaseUri();

        $crawler = $client->request('GET', $baseUri.'/admin/login');

        $button = $crawler->selectButton('SIGN IN');

        $form = $button->form(array(
            '_username' => 'root',
            '_password' => 'root',
        ));

        $client->submit($form);
    }
}