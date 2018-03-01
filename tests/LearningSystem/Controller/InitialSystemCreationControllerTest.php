<?php

namespace Tests\LearningSystem\Controller;

use Faker\Factory;
use LearningSystem\Business\Controller\InitialSystemCreationController;
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
    /**
     * @var InitialSystemCreationController $initialSystemCreationController
     */
    private $initialSystemCreationController;

    public function setUp()
    {
        parent::setUp();

        $this->userDataProvider = $this->container->get('langland.data_provider.user');
        $this->learningUserDataProvider = $this->container->get('langland.data_provider.learning_user');
        $this->initialSystemCreationController = $this->container->get('learning_system.business.controller.initial_system_creation');
    }

    public function test_createInitialSystem()
    {
        $faker = Factory::create();

        $user = $this->userDataProvider->createDefaultDb($faker);
        $learningUser = $this->learningUserDataProvider->createDefaultDb($faker);

        $user->setCurrentLearningUser($learningUser);

        $this->initialSystemCreationController->createInitialDataAction($user);
    }
}