<?php

namespace TestLibrary;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LanglandAdminTestCase extends WebTestCase
{
    protected static $inst;
    /**
     * @var Client $client
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ));

        static::$inst = $this;

        static::login();
    }

    public function clientGet($url) : Crawler
    {
        return $this->client->request('GET', $url);
    }

    public static function setUpBeforeClass()
    {
        exec('/usr/bin/php /var/www/bin/console langland:reset');
    }

    public static function tearDownAfterClass()
    {
        exec('/usr/bin/php /var/www/bin/console langland:reset');

        $dirs = array(
            realpath(__DIR__.'/../uploads/images'),
            realpath(__DIR__.'/../uploads/sounds'),
        );

        foreach ($dirs as $dir) {
            foreach (new \DirectoryIterator($dir) as $fileInfo) {
                if(!$fileInfo->isDot()) {
                    if ($fileInfo->isFile()) {
                        unlink($fileInfo->getPathname());
                    }
                }
            }
        }
    }

    protected static function login()
    {
        $session = static::$inst->client->getContainer()->get('session');

        // the firewall context defaults to the firewall name
        $firewallContext = 'admin';

        $token = new UsernamePasswordToken('root', 'root', $firewallContext, array('ROLE_DEVELOPER'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        static::$inst->client->getCookieJar()->set($cookie);
    }

    protected function doTestSuccessValidation(Crawler $crawler, array $fields, string $button = 'Create')
    {
        $form = $crawler->selectButton($button)->form();

        foreach ($fields as $field) {
            if ($field['name'] === 'form[image][imageFile]') {
                $form[$field['name']]->upload($field['value']);

                continue;
            }

            if ($field['name'] === 'form[showOnPage]') {
                $form['form[showOnPage]']->tick();

                continue;
            }

            $form[$field['name']] = $field['value'];
        }

        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    protected function doTestFailedValidation(Crawler $crawler, array $fields, string $button = 'Create')
    {
        $form = $crawler->selectButton($button)->form();

        foreach ($fields as $field) {
            $form[$field['name']] = $field['value'];
        }

        $this->client->submit($form);

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    protected function doTestDashboard($dashboardRoute, $navText) : Crawler
    {
        $languageIndexLink = $this->clientGet($dashboardRoute)->selectLink($navText)->link();

        $indexCrawler = $this->client->click($languageIndexLink);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        return $indexCrawler;
    }

    protected function doTestIndex(string $dashboardRoute, string $navText, array $testables)
    {
        $link = $this->clientGet($dashboardRoute)->selectLink($navText)->link();

        $indexCrawler = $this->client->click($link);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $list = $indexCrawler->filter('.page-content .card');

        $this->assertEquals(count($testables), count($list));

        $list->each(function(Crawler $crawler) use ($testables) {
            $text = $crawler->filter('.base-action-link')->text();

            $this->assertContains($text, $testables);
        });
    }

    protected function doTestList(string $dashboardRoute, string $navText) : Crawler
    {
        $index = $this->client->click($this->clientGet($dashboardRoute)->selectLink($navText)->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $list = $index->filter('.page-content .card');

        $this->assertEquals(2, count($list));

        return $list;
    }
}