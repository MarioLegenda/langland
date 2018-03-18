<?php

namespace PublicApi\Implementation;

use ArmorBundle\Entity\User;
use PublicApi\Infrastructure\Type\CourseType;
use PublicApiBundle\Entity\LearningUser;
use TestLibrary\PublicApiTestCase;

class LearningMetadataImplementationTest extends PublicApiTestCase
{
    public function test_createLearningMetadata()
    {
        $this->prepareTest();

        $learningMetadataImplementation = $this->container->get('public_api.business.implementation.learning_metadata');

        $learningMetadata = $learningMetadataImplementation->createLearningMetadata(
            CourseType::fromValue('Beginner'),
            0,
            0
        );

        static::assertNotEmpty($learningMetadata);
        static::assertInternalType('array', $learningMetadata);
        static::assertArrayHasKey('learningMetadataId', $learningMetadata);
        static::assertInternalType('int', $learningMetadata['learningMetadataId']);
    }

    private function prepareTest(int $wordLevel = 0)
    {
        $language = $this->prepareLanguageData($wordLevel);
        $userData = $this->prepareUserData($language);

        /** @var LearningUser $learningUser */
        $learningUser = $userData['learningUser'];
        /** @var User $user */
        $user = $userData['user'];

        $user->setCurrentLearningUser($learningUser);

        $this->mockProviders($user);
    }
}