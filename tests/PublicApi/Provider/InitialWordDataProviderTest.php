<?php

namespace PublicApi\Provider;

use ArmorBundle\Entity\User;
use PublicApi\Infrastructure\Type\CourseType;
use PublicApiBundle\Entity\LearningUser;
use TestLibrary\PublicApiTestCase;
use TestLibrary\TestBuilder\AdminTestBuilder;

class InitialWordDataProviderTest extends PublicApiTestCase
{
    public function test_InitialWordDataProvider()
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

            $learningMetadataImplementation = $this->container->get('public_api.business.implementation.learning_metadata');

            $learningMetadata = $learningMetadataImplementation->createLearningMetadata(
                CourseType::fromValue('Beginner'),
                0,
                0
            );

            $learningMetadataId = $learningMetadata['learningMetadataId'];

            $initialWordDataProvider = $this->container->get('public_api.learning_system.data_provider.word_data_provider');

            $wordNumber = 20;
            $providedData = $initialWordDataProvider->getData($learningMetadataId, [
                'word_number' => $wordNumber,
                'word_level' => $i+1
            ]);

            static::assertEquals($wordNumber, count($providedData));
        }
    }

    public function prepare_test_InitialWordDataProvider(): int
    {
        $language = $this->container->get('data_provider.language')->createDefaultDb($this->getFaker());

        $this->prepareLanguageData(
            $language, [
            'courseOrder' => 0,
        ], [
            'learningOrder' => 0,
        ]);

        $userData = $this->prepareUserData($language);

        /** @var LearningUser $learningUser */
        $learningUser = $userData['learningUser'];
        /** @var User $user */
        $user = $userData['user'];

        $user->setCurrentLearningUser($learningUser);

        $this->mockProviders($user);

        $learningMetadataImplementation = $this->container->get('public_api.business.implementation.learning_metadata');

        $learningMetadata = $learningMetadataImplementation->createLearningMetadata(
            CourseType::fromValue('Beginner'),
            0,
            0
        );

        return $learningMetadata['learningMetadataId'];
    }
}