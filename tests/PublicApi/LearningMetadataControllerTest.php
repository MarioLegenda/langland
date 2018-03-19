<?php

namespace PublicApi;

use ArmorBundle\Entity\User;
use PublicApi\LearningSystem\Business\Controller\LearningMetadataController;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestLibrary\PublicApiTestCase;
use TestLibrary\TestBuilder\AdminTestBuilder;
use TestLibrary\TestBuilder\AppTestBuilder;

class LearningMetadataControllerTest extends PublicApiTestCase
{

    public function test_initial_learning_lesson_presentation()
    {
        $adminBuilder = new AdminTestBuilder($this->container);
        $language = $adminBuilder->buildAdmin();

        $appBuilder = new AppTestBuilder($this->container);

        /** @var User $user */
        $user = $appBuilder->createLearningUser($language);
        $appBuilder->makeInitialDataCreation($user);

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