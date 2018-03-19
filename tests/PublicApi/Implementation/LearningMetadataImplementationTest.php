<?php

namespace PublicApi\Implementation;

use AdminBundle\Command\Helper\FakerTrait;
use PublicApi\Infrastructure\Type\CourseType;
use TestLibrary\PublicApiTestCase;
use TestLibrary\TestBuilder\AdminTestBuilder;

class LearningMetadataImplementationTest extends PublicApiTestCase
{
    use FakerTrait;

    public function test_createLearningMetadata()
    {
        $adminBuilder = new AdminTestBuilder($this->container);
        $language = $adminBuilder->buildAdmin();

        $learningUser = $this->learningUserDataProvider->createDefaultDb($this->getFaker(), $language);

        $user = $this->userDataProvider->createDefaultDb($this->getFaker());

        $user->setCurrentLearningUser($learningUser);

        $this->userDataProvider->getRepository()->persistAndFlush($user);

        $this->mockProviders($user);

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
}