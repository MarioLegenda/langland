<?php

namespace PublicApi\Controller;

use ArmorBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestLibrary\PublicApiTestCase;
use TestLibrary\TestBuilder\AdminTestBuilder;
use TestLibrary\TestBuilder\AppTestBuilder;

class InitialDataCreationTest extends PublicApiTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function test_InitialDataCreationController()
    {
        $this->manualReset();

        $adminBuilder = new AdminTestBuilder($this->container);
        $language = $adminBuilder->buildAdmin();

        for ($i = 0; $i < 5; $i++) {
            $appBuilder = new AppTestBuilder($this->container);

            /** @var User $user */
            $user = $appBuilder->createLearningUser($language);
            $appBuilder->makeInitialDataCreation($user);

            $controller = $this->container->get('public_api.controller.initial_data_creation_controller');

            $response = $controller->makeInitialDataCreation();

            static::assertInstanceOf(JsonResponse::class, $response);
            static::assertEquals(201, $response->getStatusCode());
        }
    }
}