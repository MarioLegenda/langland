<?php

namespace PublicApi\Implementation;

use AdminBundle\Command\Helper\FakerTrait;
use ArmorBundle\Entity\User;
use PublicApi\Infrastructure\Type\CourseType;
use TestLibrary\PublicApiTestCase;
use TestLibrary\TestBuilder\AdminTestBuilder;
use TestLibrary\TestBuilder\AppTestBuilder;

class LearningMetadataImplementationTest extends PublicApiTestCase
{
    use FakerTrait;

    public function setUp()
    {
        parent::setUp();

        $adminBuilder = new AdminTestBuilder($this->container);
        $language = $adminBuilder->buildAdmin();

        $appBuilder = new AppTestBuilder($this->container);

        /** @var User $user */
        $user = $appBuilder->createLearningUser($language);
        $appBuilder->makeInitialDataCreation($user);
    }

    public function test_createLearningMetadata()
    {
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