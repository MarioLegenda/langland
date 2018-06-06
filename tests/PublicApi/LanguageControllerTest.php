<?php

namespace Tests\PublicApi;

use PublicApi\Language\Business\Controller\LanguageController;
use Symfony\Component\HttpFoundation\Response;
use TestLibrary\DataProvider\LanguageInfoDataProvider;
use TestLibrary\PublicApiTestCase;
use TestLibrary\TestBuilder\AppTestBuilder;

class LanguageControllerTest extends PublicApiTestCase
{
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

    public function test_no_register_languages()
    {
        $language1 = $this->languageDataProvider->createDefaultDb($this->faker);
        $language2 = $this->languageDataProvider->createDefaultDb($this->faker);
        $language3 = $this->languageDataProvider->createDefaultDb($this->faker);

        $appBuilder = new AppTestBuilder($this->container);

        $user = $appBuilder->createAppUser();

        $response = $this->languageController->getAllShowableLanguages($user);

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
            static::assertFalse($language['already_learning']);
        }
    }

    public function test_find_all_languages()
    {
        $this->manualReset();

        $language1 = $this->languageDataProvider->createDefaultDb($this->faker);
        $language2 = $this->languageDataProvider->createDefaultDb($this->faker);
        $language3 = $this->languageDataProvider->createDefaultDb($this->faker);

        $appBuilder = new AppTestBuilder($this->container);

        $user = $appBuilder->createAppUser();

        $appBuilder->registerLanguageSession($user, $language1);

        $response = $this->languageController->getAllShowableLanguages($user);

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

            static::assertArrayHasKey('urls', $language);
            static::assertNotEmpty($language['urls']);
        }
    }

    public function test_get_find_all_languages_with_multiple_registered()
    {
        $this->manualReset();

        $language1 = $this->languageDataProvider->createDefaultDb($this->faker);
        $language2 = $this->languageDataProvider->createDefaultDb($this->faker);
        $language3 = $this->languageDataProvider->createDefaultDb($this->faker);

        $appBuilder = new AppTestBuilder($this->container);

        $user = $appBuilder->createAppUser();

        $appBuilder->registerLanguageSession($user, $language1);
        $appBuilder->registerLanguageSession($user, $language3);
        $appBuilder->registerLanguageSession($user, $language2);

        $response = $this->languageController->getAllShowableLanguages($user);

        static::assertInstanceOf(Response::class, $response);

        $content = $response->getContent();

        static::assertNotEmpty($content);

        $content = json_decode($content, true);

        static::assertNotEmpty($content);
        static::assertInternalType('array', $content);

        static::assertEquals(6, $content['collection']['totalItems']);
    }

    public function test_find_language_info()
    {
        $language = $this->languageDataProvider->createDefaultDb($this->faker);

        $this->languageInfoDataProvider->createDefaultDb($this->faker, $language, 10);

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