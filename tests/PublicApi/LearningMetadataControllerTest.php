<?php

namespace PublicApi;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use PublicApi\LearningSystem\Business\Controller\InitialDataCreationController;
use PublicApi\LearningSystem\Business\Controller\LearningMetadataController;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestLibrary\PublicApiTestCase;
use TestLibrary\TestBuilder\AdminTestBuilder;
use TestLibrary\TestBuilder\AppTestBuilder;

class LearningMetadataControllerTest extends PublicApiTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function test_learning_lesson_presentation()
    {
        $adminBuilder = new AdminTestBuilder($this->container);
        $appBuilder = new AppTestBuilder($this->container);

        /** @var User $user */
        $user = $appBuilder->createAppUser();

        $languages = [];

        for ($i = 0; $i < 2; $i++) {
            $languages[] = $adminBuilder->buildAdmin();
        }

        /** @var Language $language */
        foreach ($languages as $language) {
            /** @var LearningUser $user */
            $learningUser = $appBuilder->createLearningUser($language);
            $user->setCurrentLearningUser($learningUser);
            $appBuilder->mockProviders($user);

            /** @var InitialDataCreationController $controller */
            $controller = $this->container->get('public_api.controller.initial_data_creation_controller');

            /** @var JsonResponse $response */
            $response = $controller->makeInitialDataCreation();

            static::assertInstanceOf(JsonResponse::class, $response);
            static::assertEquals(201, $response->getStatusCode());

            /** @var LearningMetadataController $controller */
            $controller = $this->container->get('public_api.controller.learning_metadata');

            /** @var JsonResponse $response */
            $response = $controller->getLearningLessonPresentation();

            static::assertInstanceOf(JsonResponse::class, $response);
            static::assertEquals(200, $response->getStatusCode());

            $json = $response->getContent();

            static::assertInternalType('string', $json);

            $data = json_decode($json, true);

            static::assertInternalType('array', $data);
            static::assertNotEmpty($data);
        }
    }

    public function test_learning_games_presentation()
    {
        $adminBuilder = new AdminTestBuilder($this->container);
        $appBuilder = new AppTestBuilder($this->container);

        /** @var User $user */
        $user = $appBuilder->createAppUser();

        $languages = [];

        for ($i = 0; $i < 2; $i++) {
            $languages[] = $adminBuilder->buildAdmin();
        }

        /** @var Language $language */
        foreach ($languages as $language) {
            /** @var LearningUser $user */
            $learningUser = $appBuilder->createLearningUser($language);
            $user->setCurrentLearningUser($learningUser);
            $appBuilder->mockProviders($user);

            /** @var InitialDataCreationController $controller */
            $controller = $this->container->get('public_api.controller.initial_data_creation_controller');

            /** @var JsonResponse $response */
            $response = $controller->makeInitialDataCreation();

            static::assertInstanceOf(JsonResponse::class, $response);
            static::assertEquals(201, $response->getStatusCode());

            /** @var LearningMetadataController $controller */
            $controller = $this->container->get('public_api.controller.learning_metadata');

            /** @var JsonResponse $response */
            $response = $controller->getLearningGamesPresentation();

            static::assertInstanceOf(JsonResponse::class, $response);
            static::assertEquals(200, $response->getStatusCode());

            $json = $response->getContent();

            static::assertInternalType('string', $json);

            $data = json_decode($json, true);

            static::assertInternalType('array', $data);
            static::assertNotEmpty($data);
        }
    }
}