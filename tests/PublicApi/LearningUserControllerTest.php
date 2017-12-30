<?php

namespace Tests\PublicApi;

use AdminBundle\Command\Helper\FakerTrait;
use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use PublicApi\LearningUser\Business\Controller\LearningUserController;
use PublicApi\LearningUser\Business\Implementation\LearningUserImplementation;
use PublicApi\LearningUser\Repository\LearningUserRepository;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\HttpFoundation\Response;
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
     * @var LearningUserController $learningUserController
     */
    private $learningUserController;
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
        $this->learningUserController = $this->container->get('langland.public_api.controller.learning_user');
    }

    public function test_register_learning_user()
    {
        $language = $this->languageDataProvider->createDefaultDb($this->getFaker());
        $user = $this->userDataProvider->createDefaultDb($this->getFaker());

        $response = $this->learningUserController->registerLearningUser($language, $user);

        static::assertInstanceOf(Response::class, $response);
        static::assertEquals(201, $response->getStatusCode());

        $learningUser = $this->learningUserImplementation->findExact($language, $user);

        static::assertInstanceOf(LearningUser::class, $learningUser);

        /** @var User $user */
        $user = $this->userDataProvider->getRepository()->find($user->getId());

        static::assertInstanceOf(User::class, $user);
        static::assertInstanceOf(LearningUser::class, $user->getCurrentLearningUser());
        static::assertEquals($learningUser->getId(), $user->getCurrentLearningUser()->getId());
    }

    public function test_create_new_learning_user()
    {
        $this->manualReset();

        $language = $this->languageDataProvider->createDefaultDb($this->getFaker());
        $user = $this->userDataProvider->createDefaultDb($this->getFaker());

        $response = $this->learningUserController->registerLearningUser($language, $user);

        static::assertInstanceOf(Response::class, $response);
        static::assertEquals(201, $response->getStatusCode());

        $learningUser = $this->learningUserImplementation->findExact($language, $user);

        static::assertInstanceOf(LearningUser::class, $learningUser);

        /** @var User $user */
        $user = $this->userDataProvider->getRepository()->find($user->getId());

        static::assertInstanceOf(User::class, $user);
        static::assertInstanceOf(LearningUser::class, $user->getCurrentLearningUser());
        static::assertEquals($learningUser->getId(), $user->getCurrentLearningUser()->getId());

        /** @var Language $newLanguage */
        $newLanguage = $this->languageDataProvider->createDefaultDb($this->getFaker());

        /** @var Response $response */
        $response = $this->learningUserController->registerLearningUser($newLanguage, $user);

        static::assertEquals(201, $response->getStatusCode());

        /** @var LearningUser $learningUser */
        $learningUser = $this->learningUserImplementation->findExact($newLanguage, $user);

        static::assertInstanceOf(LearningUser::class, $user->getCurrentLearningUser());
        static::assertEquals(2, count($this->learningUserRepository->findAll()));
        static::assertEquals($learningUser->getId(), $user->getCurrentLearningUser()->getId());
    }

    public function test_learning_user_switch()
    {
        $this->manualReset();

        $language = $this->languageDataProvider->createDefaultDb($this->getFaker());
        $user = $this->userDataProvider->createDefaultDb($this->getFaker());

        $response = $this->learningUserController->registerLearningUser($language, $user);

        /** @var User $user */
        $user = $this->userDataProvider->getRepository()->find($user->getId());
        $learningUser = $this->learningUserImplementation->findExact($language, $user);

        static::assertEquals(201, $response->getStatusCode());
        static::assertEquals($learningUser->getId(), $user->getCurrentLearningUser()->getId());

        $newLanguage = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $response = $this->learningUserController->registerLearningUser($newLanguage, $user);

        /** @var User $user */
        $user = $this->userDataProvider->getRepository()->find($user->getId());
        $learningUser = $this->learningUserImplementation->findExact($newLanguage, $user);

        static::assertEquals(201, $response->getStatusCode());
        static::assertEquals($learningUser->getId(), $user->getCurrentLearningUser()->getId());

        $response = $this->learningUserController->registerLearningUser($language, $user);

        /** @var User $user */
        $user = $this->userDataProvider->getRepository()->find($user->getId());
        $learningUser = $this->learningUserImplementation->findExact($language, $user);

        static::assertEquals(204, $response->getStatusCode());
        static::assertEquals($learningUser->getId(), $user->getCurrentLearningUser()->getId());
        static::assertEquals(2, count($this->learningUserRepository->findAll()));

        $response = $this->learningUserController->registerLearningUser($language, $user);

        /** @var User $user */
        $user = $this->userDataProvider->getRepository()->find($user->getId());
        $learningUser = $this->learningUserImplementation->findExact($language, $user);

        static::assertEquals(204, $response->getStatusCode());
        static::assertEquals($learningUser->getId(), $user->getCurrentLearningUser()->getId());
        static::assertEquals(2, count($this->learningUserRepository->findAll()));
    }
}