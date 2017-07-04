<?php

namespace TestLibrary;

use AdminBundle\Command\Helper\LanguageFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class LanglandUserTestCase extends WebTestCase
{
    protected static $inst;
    /**
     * @var Client $client
     */
    protected $client;

    public function setUp()
    {
        Seed::inst()->reset();

        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'marioskrlec@outlook.com',
            'PHP_AUTH_PW'   => 'root',
        ));

        static::$inst = $this;

        static::login();
    }

    protected function createLanguages(array $languages, $em)
    {
        $languageFactory = new LanguageFactory($em);
        $languageFactory->create($languages);
    }

    protected static function login()
    {
        $session = static::$inst->client->getContainer()->get('session');

        // the firewall context defaults to the firewall name
        $firewallContext = 'langland';

        $token = new UsernamePasswordToken('marioskrlec@outlook.com', 'root', $firewallContext, array('ROLE_USER'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        static::$inst->client->getCookieJar()->set($cookie);
    }
}