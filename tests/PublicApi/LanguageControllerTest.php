<?php

namespace Tests\PublicApi;

use AdminBundle\Command\Helper\FakerTrait;
use PublicApi\Language\Business\Controller\LanguageController;
use Symfony\Component\HttpFoundation\Response;
use TestLibrary\DataProvider\LanguageInfoDataProvider;
use TestLibrary\DataProvider\UserDataProvider;
use TestLibrary\LanglandAdminTestCase;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;

class LanguageControllerTest extends LanglandAdminTestCase
{
    use FakerTrait;
    /**
     * @var LanguageController $languageController
     */
    private $languageController;
    /**
     * @var LanguageDataProvider $languageDataProvider
     */
    private $languageDataProvider;
    /**
     * @var UserDataProvider $userDataProvider
     */
    private $userDataProvider;
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
        $this->languageDataProvider = $this->container->get('data_provider.language');
        $this->userDataProvider = $this->container->get('data_provider.user');
        $this->languageInfoDataProvider = $this->container->get('data_provider.language_info');
    }

    public function test_find_all_languages()
    {
        $user = $this->userDataProvider->createDefaultDb($this->getFaker());
        $this->languageDataProvider->createDefaultDb($this->getFaker());
        $this->languageDataProvider->createDefaultDb($this->getFaker());
        $this->languageDataProvider->createDefaultDb($this->getFaker());

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