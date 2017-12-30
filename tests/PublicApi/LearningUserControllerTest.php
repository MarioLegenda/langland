<?php

namespace Tests\PublicApi;

use AdminBundle\Command\Helper\FakerTrait;
use ArmorBundle\Entity\User;
use PublicApi\LearningUser\Business\Implementation\LearningUserImplementation;
use PublicApi\LearningUser\Repository\LearningUserRepository;
use PublicApiBundle\Entity\LearningUser;
use TestLibrary\ContainerAwareTest;
use TestLibrary\DataProvider\UserDataProvider;
use TestLibrary\LanglandAdminTestCase;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;

class LearningUserControllerTest extends LanglandAdminTestCase
{
    use FakerTrait;
    /**
     * @var LanguageDataProvider $languageDataProvider
     */
    private $languageDataProvider;
    /**
     * @var LearningUserImplementation $learningUserImplementation
     */
    private $learningUserImplementation;
    /**
     * @var UserDataProvider $userDataProvider
     */
    private $userDataProvider;
    /**
     * @var LearningUserRepository $learningUserRepository
     */
    private $learningUserRepository;

    public function setUp()
    {
        parent::setUp();

        $this->languageDataProvider = $this->container->get('langland.data_provider.language');
        $this->learningUserImplementation = $this->container->get('langland.public_api.business.implementation.learning_user');
        $this->userDataProvider = $this->container->get('langland.data_provider.user');
        $this->learningUserRepository = $this->container->get('langland.public_api.repository.learning_user');
    }

    public function test_register_learning_user()
    {
        $language = $this->languageDataProvider->createDefaultDb($this->getFaker());
        $user = $this->userDataProvider->createDefaultDb($this->getFaker());

        $this->learningUserImplementation->registerLearningUser(
            $language,
            $user
        );

        $learningUser = $this->learningUserRepository->findOneBy([
            'user' => $user,
            'language' => $language,
        ]);

        static::assertInstanceOf(LearningUser::class, $learningUser);

        return [
            'language' => $language,
            'user' => $user,
        ];
    }

    public function test_add_language_to_existing_learning_user()
    {
        $language = $this->languageDataProvider->createDefaultDb($this->getFaker());
        $user = $this->userDataProvider->createDefaultDb($this->getFaker());

        $this->learningUserImplementation->registerLearningUser(
            $language,
            $user
        );

        /** @var LearningUser $learningUser */
        $learningUser = $this->learningUserRepository->findOneBy([
            'user' => $user,
            'language' => $language,
        ]);

        static::assertInstanceOf(LearningUser::class, $learningUser);
        static::assertEquals(2, count($this->learningUserRepository->findAll()));

        $newLanguage = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $this->learningUserImplementation->registerLearningUser(
            $newLanguage,
            $user
        );

        static::assertEquals(3, count($this->learningUserRepository->findAll()));
    }
}