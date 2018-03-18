<?php

namespace PublicApi\Controller;

use ArmorBundle\Entity\User;
use PublicApi\Infrastructure\Type\CourseType;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestLibrary\PublicApiTestCase;

class InitialDataCreationTest extends PublicApiTestCase
{
    public function test_InitialDataCreationController()
    {
        $this->manualReset();

        $numberOfLanguages = 3;
        $wordLevels = [1, 2, 3];

        for ($i = 0; $i < $numberOfLanguages; $i++) {
            $language = $this->prepareLanguageData($wordLevels[$i]);
            $userData = $this->prepareUserData($language);

            /** @var LearningUser $learningUser */
            $learningUser = $userData['learningUser'];
            /** @var User $user */
            $user = $userData['user'];

            $mockedProviderData = $this->mockProviders($user);

            foreach ($wordLevels as $level) {
                $this->createWords($language,50, [
                    'level' => $level,
                ]);
            }

            $wordNumbers = [15, 17, 20];

            foreach ($wordNumbers as $wordNumber) {
                $this->mockInitialWordDataProvider(
                    $mockedProviderData['learningUserProvider'],
                    $mockedProviderData['languageProvider']
                );

                $controller = $this->container->get('public_api.controller.initial_data_creation_controller');

                $response = $controller->makeInitialDataCreation();

                static::assertInstanceOf(JsonResponse::class, $response);
                static::assertEquals(201, $response->getStatusCode());
            }
        }
    }
}