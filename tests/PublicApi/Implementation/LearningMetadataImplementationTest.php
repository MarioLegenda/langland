<?php

namespace PublicApi\Implementation;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use PublicApi\LearningSystem\Business\Implementation\LearningMetadataImplementation;
use PublicApi\LearningSystem\Repository\LearningLessonRepository;
use PublicApiBundle\Entity\LearningLesson;
use TestLibrary\PublicApiTestCase;
use TestLibrary\TestBuilder\AdminTestBuilder;
use TestLibrary\TestBuilder\AppTestBuilder;

class LearningMetadataImplementationTest extends PublicApiTestCase
{
    /**
     * @var LearningLessonRepository $learningLessonRepository
     */
    private $learningLessonRepository;

    public function setUp()
    {
        parent::setUp();

        $this->learningLessonRepository = $this->container->get('public_api.repository.learning_system.learning_lesson');
    }

    public function test_create_learning_metadata()
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
            $appBuilder->registerLanguageSession($user, $language);

            /** @var LearningMetadataImplementation $learningMetadataImplementation */
            $learningMetadataImplementation = $this->container->get('public_api.business.implementation.learning_metadata');

            $learningMetadataImplementation->createLearningLessons();

            $learningLessons = $this->learningLessonRepository->findAll();

            static::assertGreaterThan(1, count($learningLessons));
        }
    }
}