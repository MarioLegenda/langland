<?php

namespace Tests\PublicApi;

use AdminBundle\Command\Helper\FakerTrait;
use PublicApi\Language\Business\Controller\LanguageController;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\HttpFoundation\Response;
use TestLibrary\DataProvider\LanguageInfoDataProvider;
use TestLibrary\PublicApiTestCase;
use TestLibrary\TestBuilder\AppTestBuilder;

class LanguageControllerTest extends PublicApiTestCase
{
    use FakerTrait;
    /**
     * @var LanguageController $languageController
     */
    private $languageController;
    /**
     * @var LanguageInfoDataProvider $languageInfoDataProvider
     */
    private $languageInfoDataProvider;
    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->languageController = $this->container->get('public_api.controller.language');
        $this->languageInfoDataProvider = $this->container->get('data_provider.language_info');
    }

    public function test_find_all_languages()
    {
        $this->languageDataProvider->createDefaultDb($this->getFaker());
        $this->languageDataProvider->createDefaultDb($this->getFaker());
        $language = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $appBuilder = new AppTestBuilder($this->container);

        $user = $appBuilder->createAppUser();

        /** @var LearningUser $user */
        $learningUser = $appBuilder->createLearningUser($language);
        $user->setCurrentLearningUser($learningUser);
        $this->userDataProvider->getRepository()->persistAndFlush($user);
        $appBuilder->mockProviders($user);

        $response = $this->languageController->getAll($user);

        static::assertInstanceOf(Response::class, $response);

        $content = $response->getContent();

        static::assertNotEmpty($content);

        $content = json_decode($content, true);

        static::assertNotEmpty($content);
        static::assertInternalType('array', $content);

        static::assertEquals(3, $content['collection']['totalItems']);
        static::assertNotEmpty($content['collection']['data']);

        $languages = $content['collection']['data'];

        foreach ($languages as $language) {
            static::assertArrayHasKey('images', $language);
            static::assertCount(2, $language['images']);
        }
    }

    public function test_find_language_info()
    {
        $language = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $this->languageInfoDataProvider->createDefaultDb($this->getFaker(), $language, 10);

        $response = $this->languageController->getLanguageInfo($language);

        static::assertInstanceOf(Response::class, $response);

        $content = $response->getContent();

        static::assertNotEmpty($content);

        $content = json_decode($content, true);

        static::assertInternalType('array', $content);
        static::assertNotEmpty($content);

        $resource = $content['resource']['data'];

        static::assertArrayHasKey('name', $resource);
        static::assertArrayHasKey('texts', $resource);

        static::assertEquals(10, count($resource['texts']));
    }
}