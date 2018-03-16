<?php

namespace AdminBundle\Command;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use LearningSystem\Infrastructure\Type\ChallengesType;
use LearningSystem\Infrastructure\Type\FreeTimeType;
use LearningSystem\Infrastructure\Type\LearningTimeType;
use LearningSystem\Infrastructure\Type\MemoryType;
use LearningSystem\Infrastructure\Type\PersonType;
use LearningSystem\Infrastructure\Type\ProfessionType;
use LearningSystem\Infrastructure\Type\SpeakingLanguagesType;
use LearningSystem\Infrastructure\Type\StressfulJobType;
use PublicApi\Language\Infrastructure\LanguageProvider;
use PublicApi\LearningSystem\QuestionAnswersApplicationProvider;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class LearningUserSeedCommand
 * @package AdminBundle\Command
 *
 * This command should not be executed multiple times.
 * Execute langland:complete_reset first and then this command.
 * Otherwise, there could be some undesired effects
 */

class LearningUserSeedCommand extends ContainerAwareCommand
{
    /**
     * @void
     */
    public function configure()
    {
        $this
            ->setName('learning_system:public_api:seed')
            ->setDescription('Seed learning users');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->isValidEnvironment();

        $userRepository = $this->getContainer()->get('armor.repository.user');
        $languageRepository = $this->getContainer()->get('learning_metadata.repository.language');
        $learningUserController = $this->getContainer()->get('public_api.controller.learning_user');
        $learningUserImplementation = $this->getContainer()->get('public_api.business.implementation.learning_user');

        $users = $userRepository->findAll();
        $languages = $languageRepository->findAll();

        /** @var User $user */
        foreach ($users as $user) {
            /** @var Language $language */
            foreach ($languages as $language) {
                /** @var LearningUser $learningUser */
                $learningUserController->registerLearningUser($language, $user);

                $learningUser = $user->getCurrentLearningUser();
                $learningUser->setAnsweredQuestions($this->getAnsweredQuestions());

                $learningUserController->registerLearningUser($language, $user);

                $learningUserImplementation->markLanguageInfoLooked($learningUser);
                $learningUserImplementation->markQuestionsAnswered(
                    $user,
                    new QuestionAnswers($user->getCurrentLearningUser()->getAnsweredQuestions())
                );

                $this->mockProviders($user);

                $initialDataCreationImplementation = $this->getContainer()->get('learning_system.business.implementation.initial_data_creation');

                $initialDataCreationImplementation->createInitialData();
            }
        }
    }
    /**
     * @return array
     */
    private function getAnsweredQuestions(): array
    {
        return [
            SpeakingLanguagesType::getName() => 0,
            ProfessionType::getName() => 'arts_and_entertainment',
            PersonType::getName() => 'sure_thing',
            LearningTimeType::getName() => 'morning',
            FreeTimeType::getName() => '30_minutes',
            MemoryType::getName() => 'short_term',
            ChallengesType::getName() => 'dislike_challenges',
            StressfulJobType::getName() => 'stressful_job',
        ];
    }
    /**
     * @param User $user
     * @return array
     */
    private function mockProviders(User $user): array
    {
        $token = new UsernamePasswordToken($user, 'root', 'admin', ['ROLE_PUBLIC_API_USER']);
        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken($token);

        $learningUserProvider = new LearningUserProvider($tokenStorage);
        $languageProvider = new LanguageProvider($learningUserProvider);
        $questionAnswersProvider = new QuestionAnswersApplicationProvider($learningUserProvider);

        $this->getContainer()->set('public_api.provider.language', $languageProvider);
        $this->getContainer()->set('public_api.learning_user_provider', $learningUserProvider);
        $this->getContainer()->set('public_api.provider.question_answers_application_provider', $questionAnswersProvider);

        return [
            'languageProvider' => $languageProvider,
            'learningUserProvider' => $learningUserProvider,
            'questionAnswersProvider' => $questionAnswersProvider,
        ];
    }
    /**
     * @throws \RuntimeException
     */
    private function isValidEnvironment()
    {
        $env = $this->getContainer()->get('kernel')->getEnvironment();
        $validEnvironments = ['dev', 'test'];

        if (!in_array($env, $validEnvironments)) {
            $message = sprintf('This command can only be executed in \'%s\' environments', implode(', ', $validEnvironments));

            throw new \RuntimeException($message);
        }
    }
}