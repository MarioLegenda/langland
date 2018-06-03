<?php

namespace PublicApi\Provider;

use ArmorBundle\Entity\User;
use PublicApi\Infrastructure\Type\CourseType;
use PublicApiBundle\Entity\LearningUser;
use TestLibrary\PublicApiTestCase;
use TestLibrary\TestBuilder\AdminTestBuilder;
use TestLibrary\TestBuilder\AppTestBuilder;

class InitialWordDataProviderTest extends PublicApiTestCase
{
    public function test_InitialWordDataProvider()
    {
        $this->manualReset();

        $adminBuilder = new AdminTestBuilder($this->container);
        $appBuilder = new AppTestBuilder($this->container);

        $language = $adminBuilder->buildAdmin();

        /** @var User $user */
        $user = $appBuilder->createAppUser();

        for ($i = 0; $i < 5; $i++) {
            $appBuilder->registerLanguageSession($user, $language);

            $learningMetadataImplementation = $this->container->get('public_api.business.implementation.learning_metadata');

            $learningLesson = $learningMetadataImplementation->createLearningLessons();

            $initialWordDataProvider = $this->container->get('public_api.learning_system.data_provider.word_data_provider');

            $wordNumber = 20;
            $providedData = $initialWordDataProvider->getData($learningLesson, [
                'word_number' => $wordNumber,
                'word_level' => $i+1
            ]);

            static::assertEquals($wordNumber, count($providedData));
        }
    }
}