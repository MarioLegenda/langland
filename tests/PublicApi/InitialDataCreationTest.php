<?php

namespace PublicApi\Controller;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use PublicApi\LearningSystem\Business\Controller\InitialDataCreationController;
use PublicApiBundle\Entity\LearningUser;
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
        $appBuilder = new AppTestBuilder($this->container);

        /** @var User $user */
        $user = $appBuilder->createAppUser();

        /** @var Language[] $languages */
        $languages = [];

        for ($i = 0; $i < 2; $i++) {
            $languages[] = $adminBuilder->buildAdmin();
        }

        /** @var Language $language */
        foreach ($languages as $language) {
            $appBuilder->registerLanguageSession($user, $language);

            /** @var InitialDataCreationController $controller */
            $controller = $this->container->get('public_api.controller.initial_data_creation_controller');

            /** @var JsonResponse $response */
            $response = $controller->makeInitialDataCreation();

            static::assertInstanceOf(JsonResponse::class, $response);
            static::assertEquals(201, $response->getStatusCode());
        }
    }
}