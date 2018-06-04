<?php

namespace Armor;

use AdminBundle\Entity\Language;
use Armor\Controller\LanguageSessionController;
use Armor\Infrastructure\Communicator\Session\LanguageSessionCommunicator;
use Armor\Repository\LanguageSessionRepository;
use ArmorBundle\Entity\LanguageSession;
use ArmorBundle\Entity\User;
use Library\Infrastructure\Helper\SerializerWrapper;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestLibrary\PublicApiTestCase;

class LanguageSessionTest extends PublicApiTestCase
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

    public function setUp()
    {
        parent::setUp();

        $this->languageSessionController = $this->container->get('armor.controller.language_session');
        $this->serializerWrapper = $this->container->get('library.serializer_wrapper');
        $this->languageSessionRepository = $this->container->get('armor.repository.language_session');
    }

    public function test_register_language_session()
    {
        $user = $this->userDataProvider->createDefaultDb($this->faker);

        $language = $this->languageDataProvider->createDefaultDb($this->faker);

        /** @var LanguageSessionCommunicator $languageSessionCommunicator */
        $languageSessionCommunicator = $this->container->get('armor.communicator_session.language_session');

        $languageSessionCommunicator->initializeSession($language->getId());

        $jsonResponse = $this->languageSessionController->registerLanguageSession($languageSessionCommunicator, $user);

        static::assertInstanceOf(JsonResponse::class, $jsonResponse);

        $data = json_decode($jsonResponse->getContent(), true)['resource']['data'];

        $this->assertLanguageSessionCreationResponse($data);

        $languageSessionExistsException = false;

        try {
            $this->languageSessionController->registerLanguageSession($languageSessionCommunicator, $user);
        } catch (\RuntimeException $e) {
            $languageSessionExistsException = true;
        }

        static::assertTrue($languageSessionExistsException);
    }

    public function test_register_multiple_sessions()
    {
        $user = $this->userDataProvider->createDefaultDb($this->faker);

        /** @var Language[] $languages */
        $languages = [];
        for ($i = 0; $i < 10; $i++) {
            $languages[] = $this->languageDataProvider->createDefaultDb($this->faker);
        }

        /** @var LanguageSessionCommunicator $languageSessionCommunicator */
        $languageSessionCommunicator = $this->container->get('armor.communicator_session.language_session');

        foreach ($languages as $language) {
            $languageSessionCommunicator->initializeSession($language->getId());

            $jsonResponse = $this->languageSessionController->registerLanguageSession($languageSessionCommunicator, $user);

            $data = json_decode($jsonResponse->getContent(), true)['resource']['data'];

            $this->assertLanguageSessionCreationResponse($data);
        }
    }

    public function test_change_language_session()
    {
        $user = $this->userDataProvider->createDefaultDb($this->faker);

        /** @var Language[] $languages */
        $languages = [];
        for ($i = 0; $i < 10; $i++) {
            $languages[] = $this->languageDataProvider->createDefaultDb($this->faker);
        }

        /** @var LanguageSessionCommunicator $languageSessionCommunicator */
        $languageSessionCommunicator = $this->container->get('armor.communicator_session.language_session');

        foreach ($languages as $language) {
            $languageSessionCommunicator->initializeSession($language->getId());

            $jsonResponse = $this->languageSessionController->registerLanguageSession($languageSessionCommunicator, $user);

            $data = json_decode($jsonResponse->getContent(), true)['resource']['data'];

            /** @var LanguageSession $languageSession */
            $languageSession = $this->languageSessionRepository->find($data['id']);

            static::assertInstanceOf(LanguageSession::class, $languageSession);

            $this->assertLanguageSessionCreationResponse($data);

            $this->languageSessionController->changeLanguageSession($languageSession, $user);

            /** @var User $freshUser */
            $freshUser = $this->userDataProvider->getRepository()->findOneBy([
                'id' => $user->getId(),
            ]);

            static::assertEquals(
                $freshUser->getCurrentLanguageSession()->getId(),
                $languageSession->getId()
            );
        }
    }

    public function test_get_current_language_session()
    {
        $user = $this->userDataProvider->createDefaultDb($this->faker);

        $language = $this->languageDataProvider->createDefaultDb($this->faker);

        /** @var LanguageSessionCommunicator $languageSessionCommunicator */
        $languageSessionCommunicator = $this->container->get('armor.communicator_session.language_session');

        $languageSessionCommunicator->initializeSession($language->getId());

        $jsonResponse = $this->languageSessionController->registerLanguageSession($languageSessionCommunicator, $user);

        static::assertInstanceOf(JsonResponse::class, $jsonResponse);

        $data = json_decode($jsonResponse->getContent(), true)['resource']['data'];

        $this->assertLanguageSessionCreationResponse($data);

        $languageSessionExistsException = false;

        try {
            $this->languageSessionController->registerLanguageSession($languageSessionCommunicator, $user);
        } catch (\RuntimeException $e) {
            $languageSessionExistsException = true;
        }

        static::assertTrue($languageSessionExistsException);

        $jsonResponse = $this->languageSessionController->getCurrentLanguageSession($user);

        static::assertInstanceOf(JsonResponse::class, $jsonResponse);

        $data = json_decode($jsonResponse->getContent(), true)['resource']['data'];

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
    /**
     * @param array $data
     */
    private function assertLanguageSessionCreationResponse(array $data)
    {
        static::assertArrayHasKey('id', $data);
        static::assertNotEmpty($data['id']);

        static::assertArrayHasKey('language', $data);
        static::assertNotEmpty($data['language']);

        static::assertArrayHasKey('learningUserId', $data);
        static::assertNotEmpty($data['learningUserId']);

        static::assertArrayHasKey('languageSessions', $data);
        static::assertNotEmpty($data['languageSessions']);

        static::assertArrayHasKey('createdAt', $data);
        static::assertNotEmpty($data['createdAt']);

        static::assertArrayHasKey('updatedAt', $data);
        static::assertNotEmpty($data['updatedAt']);
    }
}