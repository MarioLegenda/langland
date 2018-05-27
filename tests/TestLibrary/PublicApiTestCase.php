<?php

namespace TestLibrary;

use AdminBundle\Entity\Language;
use AdminBundle\Command\Helper\FakerTrait;
use AdminBundle\Entity\Lesson;
use ArmorBundle\Entity\User;
use PublicApi\Infrastructure\Type\CourseType;
use PublicApi\Language\Infrastructure\LanguageProvider;
use PublicApi\LearningSystem\QuestionAnswersApplicationProvider;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use TestLibrary\DataProvider\LearningUserDataProvider;
use TestLibrary\DataProvider\UserDataProvider;
use TestLibrary\DataProvider\WordDataProvider;
use Tests\TestLibrary\DataProvider\CourseDataProvider;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;
use Tests\TestLibrary\DataProvider\LessonDataProvider;
use PublicApi\LearningSystem\Infrastructure\DataProvider\InitialWordDataProvider;
use PublicApi\LearningSystem\Business\Implementation\LearningMetadataImplementation;

class PublicApiTestCase extends WebTestCase
{
    use FakerTrait;
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
     * @var CourseDataProvider $courseDataProvider
     */
    protected $courseDataProvider;
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

        $this->client = static::createClient();

        $this->container = $this->client->getContainer();

        $this->languageDataProvider = $this->container->get('data_provider.language');
        $this->userDataProvider = $this->container->get('data_provider.user');
        $this->lessonDataProvider = $this->container->get('data_provider.lesson');
        $this->courseDataProvider = $this->container->get('data_provider.course');
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