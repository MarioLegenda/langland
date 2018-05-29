<?php

namespace PublicApi\Implementation;

use AdminBundle\Command\Helper\FakerTrait;
use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use PublicApiBundle\Entity\LearningLesson;
use PublicApiBundle\Entity\LearningUser;
use TestLibrary\PublicApiTestCase;
use TestLibrary\TestBuilder\AdminTestBuilder;
use TestLibrary\TestBuilder\AppTestBuilder;

class LearningMetadataImplementationTest extends PublicApiTestCase
{
    use FakerTrait;

    public function test_createLearningMetadata()
    {
        $adminBuilder = new AdminTestBuilder($this->container);
        $appBuilder = new AppTestBuilder($this->container);

        /** @var User $user */
        $user = $appBuilder->createAppUser();

        $languages = [];

        for ($i = 0; $i < 2; $i++) {
            $languages[] = $adminBuilder->buildAdmin();
        }

        /** @var Language $language */
        foreach ($languages as $language) {
            /** @var LearningUser $user */
            $learningUser = $appBuilder->createLearningUser($language);
            $user->setCurrentLearningUser($learningUser);
            $appBuilder->mockProviders($user);

            $learningMetadataImplementation = $this->container->get('public_api.business.implementation.learning_metadata');

            $learningLesson = $learningMetadataImplementation->createLearningMetadata();

            static::assertNotEmpty($learningLesson);
            static::assertInstanceOf(LearningLesson::class, $learningLesson);
        }
    }
}