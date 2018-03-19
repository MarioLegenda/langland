<?php

namespace PublicApi\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use TestLibrary\PublicApiTestCase;
use TestLibrary\TestBuilder\AdminTestBuilder;

class InitialDataCreationTest extends PublicApiTestCase
{
    public function test_InitialDataCreationController()
    {
        $this->manualReset();

        $adminBuilder = new AdminTestBuilder($this->container);
        $language = $adminBuilder->buildAdmin();

        $user = $this->userDataProvider->createDefaultDb($this->getFaker());

        for ($i = 0; $i < 5; $i++) {
            $learningUser = $this->learningUserDataProvider->createDefaultDb($this->getFaker(), $language);

            $user->setCurrentLearningUser($learningUser);

            $this->userDataProvider->getRepository()->persistAndFlush($user);

            $this->mockProviders($user);

            $controller = $this->container->get('public_api.controller.initial_data_creation_controller');

            $response = $controller->makeInitialDataCreation();

            static::assertInstanceOf(JsonResponse::class, $response);
            static::assertEquals(201, $response->getStatusCode());
        }
    }
}