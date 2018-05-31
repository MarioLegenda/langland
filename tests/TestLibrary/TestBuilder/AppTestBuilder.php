<?php

namespace TestLibrary\TestBuilder;

use AdminBundle\Command\Helper\FakerTrait;
use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use PublicApi\Infrastructure\Type\CourseType;
use PublicApi\Language\Infrastructure\LanguageProvider;
use PublicApi\LearningSystem\QuestionAnswersApplicationProvider;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
use PublicApiBundle\Entity\LearningUser;
use RDV\SymfonyContainerMocks\DependencyInjection\TestContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use TestLibrary\DataProvider\LearningUserDataProvider;
use TestLibrary\DataProvider\UserDataProvider;

class AppTestBuilder
{
    use FakerTrait;
    /**
     * @var ContainerInterface|TestContainer $container
     */
    private $container;
    /**
     * @var LearningUserDataProvider
     */
    private $learningUserDataProvider;
    /**
     * @var UserDataProvider
     */
    private $userDataProvider;
    /**
     * AppBuilder constructor.
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;

        $this->learningUserDataProvider = $container->get('data_provider.learning_user');
        $this->userDataProvider = $container->get('data_provider.user');
    }
    /**
     * @param Language $language
     * @return LearningUser
     */
    public function createLearningUser(Language $language): LearningUser
    {
        return $this->learningUserDataProvider->createDefaultDb($this->getFaker(), $language);
    }
    /**
     * @return User
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createAppUser(): User
    {
        $user = $this->userDataProvider->createDefaultDb($this->getFaker());

        return $this->userDataProvider->getRepository()->persistAndFlush($user);
    }
    /**
     * @param User $user
     */
    public function makeInitialDataCreation(User $user)
    {
        $this->mockProviders($user);

        $controller = $this->container->get('public_api.controller.initial_data_creation_controller');

        $controller->makeInitialDataCreation();
    }
    /**
     * @param User $user
     * @return int
     */
    public function createLearningMetadata(
        User $user
    ): int {
        $this->mockProviders($user);

        $learningMetadataImplementation = $this->container->get('public_api.business.implementation.learning_metadata');

        $learningMetadata = $learningMetadataImplementation->createLearningMetadata();

        return $learningMetadata['learningMetadataId'];
    }
    /**
     * @param User $user
     * @return array
     */
    public function mockProviders(User $user): array
    {
        $token = new UsernamePasswordToken($user, 'root', 'admin', ['ROLE_PUBLIC_API_USER']);
        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken($token);

        $learningUserProvider = new LearningUserProvider($tokenStorage);
        $languageProvider = new LanguageProvider($learningUserProvider);
        $questionAnswersProvider = new QuestionAnswersApplicationProvider($learningUserProvider);

        $this->container->set('public_api.provider.language_provider', $languageProvider);
        $this->container->set('public_api.learning_user_provider', $learningUserProvider);
        $this->container->set('public_api.provider.question_answers_application_provider', $questionAnswersProvider);

        return [
            'languageProvider' => $languageProvider,
            'learningUserProvider' => $learningUserProvider,
            'questionAnswersProvider' => $questionAnswersProvider,
        ];
    }
}