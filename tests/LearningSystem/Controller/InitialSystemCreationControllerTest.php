<?php

namespace Tests\LearningSystem\Controller;

use ArmorBundle\Entity\User;
use Faker\Factory;
use Faker\Generator;
use LearningSystem\Business\Controller\InitialSystemCreationController;
use PublicApi\LearningSystem\QuestionAnswersApplicationProvider;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use TestLibrary\DataProvider\LearningUserDataProvider;
use TestLibrary\DataProvider\UserDataProvider;
use TestLibrary\LanglandAdminTestCase;

class InitialSystemCreationControllerTest extends LanglandAdminTestCase
{
    /**
     * @var LearningUserDataProvider $learningUserDataProvider
     */
    private $learningUserDataProvider;
    /**
     * @var UserDataProvider $userDataProvider
     */
    private $userDataProvider;

    public function setUp()
    {
        parent::setUp();

        $this->userDataProvider = $this->container->get('langland.data_provider.user');
        $this->learningUserDataProvider = $this->container->get('langland.data_provider.learning_user');
    }

    public function test_createInitialSystem()
    {
        $faker = Factory::create();

        $user = $this->userDataProvider->createDefaultDb($faker);
        $learningUser = $this->learningUserDataProvider->createDefaultDb($faker);

        $user->setCurrentLearningUser($learningUser);

        $this->createQuestionAnswersProviderMock($user);

        $initialSystemCreationController = $this->container->get('learning_system.business.controller.initial_system_creation');

        $initialSystemCreationController->createInitialDataAction($user);
    }
    /**
     * @param User $user
     */
    private function createQuestionAnswersProviderMock(User $user)
    {
        $token = new UsernamePasswordToken($user, null, 'admin', ['ROLE_PUBLIC_API_USER']);

        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken($token);

        $learningUserProvider = new LearningUserProvider($tokenStorage);

        $questionAnswersResolver = new QuestionAnswersApplicationProvider($learningUserProvider);

        $this->container->set('langland.public_api.question_answers_application_provider', $questionAnswersResolver);
    }
}