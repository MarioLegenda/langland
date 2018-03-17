<?php

namespace LearningSystem\Controller;

use ArmorBundle\Entity\User;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestLibrary\PublicApiTestCase;

class InitialDataCreationControllerTest extends PublicApiTestCase
{
    public function test_InitialWordDataProvider_Controller()
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

            $mockedProviderData = $this->mockLearningUserProvider($user);

            $this->prepareLearningMetadata($learningUser);

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

                $controller = $this->container->get('learning_system.business.controller.initial_data_creation');

                $response = $controller->makeInitialDataCreation();

                static::assertInstanceOf(JsonResponse::class, $response);
                static::assertEquals(201, $response->getStatusCode());
            }
        }
    }
}