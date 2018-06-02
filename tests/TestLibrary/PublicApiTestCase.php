<?php

namespace TestLibrary;

use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TestLibrary\DataProvider\LearningUserDataProvider;
use TestLibrary\DataProvider\UserDataProvider;
use TestLibrary\DataProvider\WordDataProvider;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;
use Tests\TestLibrary\DataProvider\LessonDataProvider;

class PublicApiTestCase extends WebTestCase
{
    /**
     * @var Generator $faker
     */
    protected $faker;
    /**
     * @var LanguageDataProvider $languageDataProvider
     */
    protected $languageDataProvider;
    /**
     * @var UserDataProvider $userDataProvider
     */
    protected $userDataProvider;
    /**
     * @var LessonDataProvider $lessonDataProvider
     */
    protected $lessonDataProvider;
    /**
     * @var LearningUserDataProvider $learningUserDataProvider
     */
    protected $learningUserDataProvider;
    /**
     * @var WordDataProvider $wordDataProvider
     */
    protected $wordDataProvider;
    /**
     * @var Client $client
     */
    protected $client;
    /**
     * @var ContainerInterface $container
     */
    protected $container;

    public function setUp()
    {
        parent::setUp();

        $this->faker = Factory::create();

        $this->client = static::createClient();

        $this->container = $this->client->getContainer();

        $this->languageDataProvider = $this->container->get('data_provider.language');
        $this->userDataProvider = $this->container->get('data_provider.user');
        $this->lessonDataProvider = $this->container->get('data_provider.lesson');
        $this->learningUserDataProvider = $this->container->get('data_provider.learning_user');
        $this->wordDataProvider = $this->container->get('data_provider.word');
    }
    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        exec('/usr/bin/php /var/www/bin/console langland:learning_metadata:reset');
    }
    /**
     * @inheritdoc
     */
    public static function tearDownAfterClass()
    {
        exec('/usr/bin/php /var/www/bin/console langland:learning_metadata:reset');

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
    protected function manualReset(): void
    {
        exec('/usr/bin/php /var/www/bin/console langland:learning_metadata:reset');
    }
}