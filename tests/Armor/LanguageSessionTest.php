<?php

namespace Armor;

use Armor\Controller\LanguageSessionController;
use Armor\Infrastructure\Communicator\Session\LanguageSessionCommunicator;
use Library\Infrastructure\Helper\SerializerWrapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestLibrary\PublicApiTestCase;

class LanguageSessionTest extends PublicApiTestCase
{
    /**
     * @var LanguageSessionController $languageSessionController
     */
    private $languageSessionController;
    /**
     * @var SerializerWrapper $serializerWrapper
     */
    private $serializerWrapper;

    public function setUp()
    {
        parent::setUp();

        $this->languageSessionController = $this->container->get('armor.controller.language_session');

        $this->serializerWrapper = $this->container->get('library.serializer_wrapper');
    }

    public function test_register_language_session()
    {
        $language = $this->languageDataProvider->createDefaultDb($this->faker);

        /** @var LanguageSessionCommunicator $languageSessionCommunicator */
        $languageSessionCommunicator = $this->container->get('armor.communicator_session.language_session');

        $languageSessionCommunicator->initializeSession($language->getId());

        $this->lessonDataProvider->createDefaultDb($this->faker);

        $user = $this->userDataProvider->createDefaultDb($this->faker);

        $jsonResponse = $this->languageSessionController->registerLanguageSession($languageSessionCommunicator, $user);

        static::assertInstanceOf(JsonResponse::class, $jsonResponse);

        $data = json_decode($jsonResponse->getContent(), true)['resource']['data'];

        static::assertArrayHasKey('id', $data);
        static::assertNotEmpty($data['id']);

        static::assertArrayHasKey('language', $data);
        static::assertNotEmpty($data['language']);

        static::assertArrayHasKey('learningUserId', $data);
        static::assertNotEmpty($data['learningUserId']);

        static::assertArrayHasKey('createdAt', $data);
        static::assertNotEmpty($data['createdAt']);

        static::assertArrayHasKey('updatedAt', $data);
        static::assertNotEmpty($data['updatedAt']);
    }
}