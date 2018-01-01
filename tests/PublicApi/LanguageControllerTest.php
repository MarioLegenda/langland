<?php

namespace Tests\PublicApi;

use AdminBundle\Command\Helper\FakerTrait;
use PublicApi\Language\Business\Controller\LanguageController;
use Symfony\Component\HttpFoundation\Response;
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
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->languageController = $this->container->get('langland.public_api.controller.language');
        $this->languageDataProvider = $this->container->get('langland.data_provider.language');
        $this->userDataProvider = $this->container->get('langland.data_provider.user');
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

        static::assertEquals(3, count($content));

        foreach ($content as $language) {
            static::assertArrayHasKey('images', $language);
            static::assertCount(2, $language['images']);
        }
    }
}