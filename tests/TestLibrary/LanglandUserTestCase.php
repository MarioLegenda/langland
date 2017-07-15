<?php

namespace TestLibrary;

use AdminBundle\Command\Helper\CourseFactory;
use AdminBundle\Command\Helper\LanguageFactory;
use AdminBundle\Command\Helper\LanguageInfoFactory;
use ArmorBundle\Entity\User;
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

    protected function createLanguageInfos(array $languages, $em)
    {
        $languageFactory = new LanguageFactory($em);
        $languageInfoFactory = new LanguageInfoFactory($em);

        $languageObjects = $languageFactory->create($languages, true);

        foreach ($languageObjects as $languageObject) {
            $languageInfoFactory->create($languageObject);
        }

        return $languageObjects;
    }

    protected function createLanguageCourses(array $languages, $em)
    {
        $courseFactory = new CourseFactory($em);

        foreach ($languages as $languageObject) {
            $courseFactory->create($languageObject, 6);
        }
    }

    protected static function login()
    {
        $session = static::$inst->client->getContainer()->get('session');

        // the firewall context defaults to the firewall name
        $firewallContext = 'langland';

        $user = new User();
        $user->setUsername('marioskrlec@outlook.com');
        $user->setPassword('root');
        $user->setName('Mile');
        $user->setLastname('Mile');
        $user->setRoles(array('ROLE_USER'));
        $user->setEnabled(1);
        $user->setGender('male');

        $token = new UsernamePasswordToken($user, $user->getPassword(), $firewallContext, $user->getRoles());
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();
        static::$inst->client->getContainer()->get("security.token_storage")->setToken($token);

        $cookie = new Cookie($session->getName(), $session->getId());
        static::$inst->client->getCookieJar()->set($cookie);
    }
}