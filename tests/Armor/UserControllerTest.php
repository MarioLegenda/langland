<?php

namespace Armor;

use Armor\Controller\LanguageSessionController;
use Armor\Controller\UserController;
use Armor\Infrastructure\Communication\LanguageSessionCommunicator;
use Armor\Repository\LanguageSessionRepository;
use LearningMetadata\Infrastructure\Communication\PublicApiLanguageCommunicator;
use Library\Infrastructure\Helper\SerializerWrapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestLibrary\PublicApiTestCase;

class UserControllerTest extends PublicApiTestCase
{
    /**
     * @var LanguageSessionRepository $languageSessionRepository
     */
    private $languageSessionRepository;
    /**
     * @var LanguageSessionController $languageSessionController
     */
    private $languageSessionController;
    /**
     * @var SerializerWrapper $serializerWrapper
     */
    private $serializerWrapper;
    /**
     * @var UserController $userController
     */
    private $userController;
    /**
     * @var PublicApiLanguageCommunicator $armorPublicApiLanguageCommunicator
     */
    private $learningMetadataPublicApiLanguageCommunicator;

    public function setUp()
    {
        parent::setUp();

        $this->languageSessionController = $this->container->get('armor.controller.language_session');
        $this->serializerWrapper = $this->container->get('library.serializer_wrapper');
        $this->languageSessionRepository = $this->container->get('armor.repository.language_session');
        $this->userController = $this->container->get('armor.controller.user');
        $this->learningMetadataPublicApiLanguageCommunicator = $this->container->get('learning_metadata.communication.public_api_language');
    }

    public function test_get_logged_in_public_user()
    {
        $user = $this->userDataProvider->createDefaultDb($this->faker);

        $language = $this->languageDataProvider->createDefaultDb($this->faker);

        $this->learningMetadataPublicApiLanguageCommunicator->createPublicApiLanguageFromLanguage($language, [
            'alreadyLearning' => false,
        ]);

        /** @var LanguageSessionCommunicator $languageSessionCommunicator */
        $languageSessionCommunicator = $this->container->get('armor.communicator_session.language_session');

        $languageSessionCommunicator->initializeSession($language->getId());

        $jsonResponse = $this->languageSessionController->registerLanguageSession($languageSessionCommunicator, $user);

        static::assertInstanceOf(JsonResponse::class, $jsonResponse);

        $data = json_decode($jsonResponse->getContent(), true)['resource']['data'];

        $this->assertLanguageSessionCreationResponse($data);

        $jsonResponse = $this->userController->getLoggedInPublicUserAction($user);

        static::assertInstanceOf(JsonResponse::class, $jsonResponse);

        $data = json_decode($jsonResponse->getContent(), true)['resource']['data'];

        static::assertArrayHasKey('id', $data);
        static::assertArrayHasKey('current_language_session', $data);
        static::assertNotEmpty($data['current_language_session']);

        $currentLanguageSession = $data['current_language_session'];

        static::assertArrayHasKey('learning_user', $currentLanguageSession);
        static::assertNotEmpty($currentLanguageSession['learning_user']);
        static::assertArrayHasKey('language', $currentLanguageSession['learning_user']);
        static::assertNotEmpty($currentLanguageSession['learning_user']['language']);

        static::assertInternalType('bool', $currentLanguageSession['learning_user']['is_language_info_looked']);
        static::assertInternalType('bool', $currentLanguageSession['learning_user']['are_questions_looked']);
        static::assertInternalType('string', $currentLanguageSession['learning_user']['created_at']);
        static::assertInternalType('string', $currentLanguageSession['learning_user']['updated_at']);

        static::assertInternalType('string', $currentLanguageSession['created_at']);
        static::assertInternalType('string', $currentLanguageSession['updated_at']);

    }
    /**
     * @param array $data
     */
    private function assertLanguageSessionCreationResponse(array $data)
    {
        static::assertArrayHasKey('id', $data);
        static::assertArrayHasKey('learning_user', $data);
        static::assertNotEmpty($data['learning_user']);
        static::assertArrayHasKey('language', $data['learning_user']);
        static::assertNotEmpty($data['learning_user']['language']);

        static::assertInternalType('bool', $data['learning_user']['is_language_info_looked']);
        static::assertInternalType('bool', $data['learning_user']['are_questions_looked']);
        static::assertInternalType('string', $data['learning_user']['created_at']);
        static::assertInternalType('string', $data['learning_user']['updated_at']);

        static::assertInternalType('string', $data['created_at']);
        static::assertInternalType('string', $data['updated_at']);
    }
}