<?php

namespace TestLibrary;

use RDV\SymfonyContainerMocks\DependencyInjection\TestContainer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LanglandAdminTestCase extends WebTestCase
{
    protected static $inst;
    /**
     * @var Client $client
     */
    protected $client;
    /**
     * @var TestContainer $container
     */
    protected $container;
    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'root',
            'PHP_AUTH_PW'   => 'root',
        ));

        $this->container = $this->client->getContainer();

        static::$inst = $this;

        static::login();
    }
    /**
     * @param $url
     * @return Crawler
     */
    public function clientGet($url) : Crawler
    {
        return $this->client->request('GET', $url);
    }
    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        exec('/usr/bin/php /var/www/bin/console database:truncate');
    }
    /**
     * @inheritdoc
     */
    public static function tearDownAfterClass()
    {
        exec('/usr/bin/php /var/www/bin/console database:truncate');

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
    /**
     * @void
     */
    protected static function login()
    {
        $session = static::$inst->client->getContainer()->get('session');

        // the firewall context defaults to the firewall name
        $firewallContext = 'admin';

        $token = new UsernamePasswordToken('root', 'root', $firewallContext, array('ROLE_ADMIN'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        static::$inst->client->getCookieJar()->set($cookie);
    }
    /**
     * @void
     */
    protected function manualReset(): void
    {
        exec('/usr/bin/php /var/www/bin/console database:truncate');
    }
    /**
     * @param Crawler $crawler
     * @param array $fields
     * @param string $button
     */
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
    /**
     * @param Crawler $crawler
     * @param array $fields
     * @param string $button
     */
    protected function doTestFailedValidation(Crawler $crawler, array $fields, string $button = 'Create')
    {
        $form = $crawler->selectButton($button)->form();

        foreach ($fields as $field) {
            $form[$field['name']] = $field['value'];
        }

        $this->client->submit($form);

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test that dashboard resource exists and that listing page exists
     *
     * @param $dashboardRoute
     * @param $navText
     * @return Crawler
     */
    protected function doTestDashboard($dashboardRoute, $navText) : Crawler
    {
        $languageIndexLink = $this->clientGet($dashboardRoute)->selectLink($navText)->link();

        $indexCrawler = $this->client->click($languageIndexLink);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        return $indexCrawler;
    }
    /**
     * @param string $dashboardRoute
     * @param string $navText
     * @param array $testables
     * @return Crawler
     */
    protected function doTestIndex(string $dashboardRoute, string $navText, array $testables) : Crawler
    {
        $link = $this->clientGet($dashboardRoute)->selectLink($navText)->link();

        $indexCrawler = $this->client->click($link);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $list = $indexCrawler->filter('.gradeA');

        $this->assertEquals(count($testables), count($list));

        $list->each(function(Crawler $crawler) use ($testables) {
            $text = $crawler->filter('.sorting_1')->text();

            $this->assertContains($text, $testables);
        });

        return $indexCrawler;
    }

    /**
     * Test that a list of created resource exists and that there are two of them
     * @param string $dashboardRoute
     * @param string $navText
     * @return Crawler
     */
    protected function doTestList(string $dashboardRoute, string $navText) : Crawler
    {
        $index = $this->client->click($this->clientGet($dashboardRoute)->selectLink($navText)->link());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $list = $index->filter('.gradeA');

        $this->assertEquals(2, count($list));

        return $list;
    }
}