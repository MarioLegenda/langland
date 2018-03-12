<?php

namespace Tests\PublicApi;

use AdminBundle\Command\Helper\FakerTrait;
use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use ArmorBundle\Repository\UserRepository;
use LearningSystem\Infrastructure\Questions;
use LearningSystem\Infrastructure\Type\ChallengesType;
use LearningSystem\Infrastructure\Type\FreeTimeType;
use LearningSystem\Infrastructure\Type\LearningTimeType;
use LearningSystem\Infrastructure\Type\MemoryType;
use LearningSystem\Infrastructure\Type\PersonType;
use LearningSystem\Infrastructure\Type\ProfessionType;
use LearningSystem\Infrastructure\Type\SpeakingLanguagesType;
use LearningSystem\Infrastructure\Type\StressfulJobType;
use PublicApi\Language\Business\Controller\LanguageController;
use PublicApi\Language\Repository\LanguageRepository;
use PublicApi\LearningUser\Business\Controller\LearningUserController;
use PublicApi\LearningUser\Business\Implementation\LearningUserImplementation;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswersValidator;
use PublicApi\LearningUser\Repository\LearningUserRepository;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use TestLibrary\DataProvider\QuestionsDataProvider;
use TestLibrary\DataProvider\UserDataProvider;
use TestLibrary\LanglandAdminTestCase;
use Tests\TestLibrary\DataProvider\CourseDataProvider;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;
use TestLibrary\DataProvider\LearningUserDataProvider;
use Tests\TestLibrary\DataProvider\LessonDataProvider;

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
    /**
     * @var QuestionsDataProvider $questionsDataProvider
     */
    private $questionsDataProvider;
    /**
     * @var LearningUserDataProvider $learningUserDataProvider
     */
    private $learningUserDataProvider;
    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;
    /**
     * @var LanguageController $languageController
     */
    private $languageController;
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * @var CourseDataProvider $courseDataProvider
     */
    private $courseDataProvider;
    /**
     * @var LessonDataProvider $lessonDataProvider
     */
    private $lessonDataProvider;

    public function setUp()
    {
        parent::setUp();

        $this->languageDataProvider = $this->container->get('data_provider.language');
        $this->learningUserImplementation = $this->container->get('public_api.business.implementation.learning_user');
        $this->userDataProvider = $this->container->get('data_provider.user');
        $this->learningUserRepository = $this->container->get('public_api.repository.learning_user');
        $this->learningUserController = $this->container->get('public_api.controller.learning_user');
        $this->learningUserDataProvider = $this->container->get('data_provider.learning_user');
        $this->userRepository = $this->container->get('armor.repository.user');
        $this->languageRepository = $this->container->get('public_api.repository.language');
        $this->languageController = $this->container->get('public_api.controller.language');
        $this->questionsDataProvider = $this->container->get('data_provider.questions');
        $this->lessonDataProvider = $this->container->get('data_provider.lesson');
        $this->courseDataProvider = $this->container->get('data_provider.course');
    }

    public function test_register_learning_user()
    {
        $language = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language);
        $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

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

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language);
        $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

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

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $newLanguage);
        $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

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

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language);
        $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

        $user = $this->userDataProvider->createDefaultDb($this->getFaker());

        $response = $this->learningUserController->registerLearningUser($language, $user);

        /** @var User $user */
        $user = $this->userDataProvider->getRepository()->find($user->getId());
        $learningUser = $this->learningUserImplementation->findExact($language, $user);

        static::assertEquals(201, $response->getStatusCode());
        static::assertEquals($learningUser->getId(), $user->getCurrentLearningUser()->getId());

        $newLanguage = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $newLanguage);
        $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

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

        static::assertEquals(200, $response->getStatusCode());
        static::assertEquals($learningUser->getId(), $user->getCurrentLearningUser()->getId());
        static::assertEquals(2, count($this->learningUserRepository->findAll()));

        $response = $this->learningUserController->registerLearningUser($language, $user);

        /** @var User $user */
        $user = $this->userDataProvider->getRepository()->find($user->getId());
        $learningUser = $this->learningUserImplementation->findExact($language, $user);

        static::assertEquals(200, $response->getStatusCode());
        static::assertEquals($learningUser->getId(), $user->getCurrentLearningUser()->getId());
        static::assertEquals(2, count($this->learningUserRepository->findAll()));
    }

    public function test_language_choosing_data_flow()
    {
        $this->manualReset();

        $user1 = $this->userDataProvider->createDefaultDb($this->getFaker());
        $user2 = $this->userDataProvider->createDefaultDb($this->getFaker());
        $language1 = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language1);
        $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

        $language2 = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language2);
        $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

        $language3 = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language3);
        $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

        $this->assertLanguageRegistration($language1, $user1);
        $this->assertCurrentLearningUser($language1, $user1);

        $this->assertAlreadyLearningLanguages([
            $language1
        ], $user1);

        $this->assertLanguageRegistration($language2, $user1);
        $this->assertCurrentLearningUser($language2, $user1);

        $this->assertAlreadyLearningLanguages([
            $language1,
            $language2
        ], $user1);

        $this->assertLanguageInfoNotLooked($user1);

        $this->assertLanguageRegistrationUpdate($language2, $user1);

        $this->assertLanguageInfoNotLooked($user1);

        $this->assertLanguageRegistrationUpdate($language1, $user1);

        $this->assertLanguageInfoNotLooked($user1);

        $this->assertMarkLanguageInfo($user1);

        $this->assertLanguageInfoLooked($user1);

        $this->assertLanguageRegistration($language3, $user1);
        $this->assertCurrentLearningUser($language3, $user1);

        $this->assertLanguageInfoNotLooked($user1);

        $this->assertAlreadyLearningLanguages([
            $language1,
            $language2,
            $language3,
        ], $user1);

        $this->assertLanguageRegistration($language1, $user2);
        $this->assertCurrentLearningUser($language1, $user2);

        $this->assertLanguageInfoNotLooked($user2);

        $this->assertAlreadyLearningLanguages([
            $language1
        ], $user2);

        $this->assertLanguageRegistration($language2, $user2);
        $this->assertCurrentLearningUser($language2, $user2);

        $this->assertLanguageInfoNotLooked($user2);

        $this->assertAlreadyLearningLanguages([
            $language1,
            $language2
        ], $user2);

        $this->assertMarkLanguageInfo($user2);

        $this->assertLanguageInfoLooked($user2);

        $this->assertMarkLanguageInfo($user1);

        $this->assertLanguageInfoLooked($user1);

        $this->assertLanguageRegistrationUpdate($language2, $user1);

        $this->assertMarkLanguageInfo($user1);

        $this->assertLanguageInfoLooked($user1);
    }

    public function test_dynamic_components_status()
    {
        $this->manualReset();

        $user1 = $this->userDataProvider->createDefaultDb($this->getFaker());
        $language1 = $this->languageDataProvider->createDefaultDb($this->getFaker());
        $language2 = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language1);
        $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language2);
        $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

        $this->assertLanguageRegistration($language1, $user1);
        $this->assertCurrentLearningUser($language1, $user1);

        $response = $this->learningUserController->getDynamicComponentsStatus($user1);

        static::assertInstanceOf(Response::class, $response);
        static::assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        static::assertNotEmpty($content);
        static::assertInternalType('array', $content);

        $data = $content['resource']['data'];

        static::assertArrayHasKey('isLanguageInfoLooked', $data);
        static::assertArrayHasKey('areQuestionsLooked', $data);

        static::assertFalse($data['isLanguageInfoLooked']);
        static::assertFalse($data['areQuestionsLooked']);

        $this->assertMarkLanguageInfo($user1);

        $response = $this->learningUserController->getDynamicComponentsStatus($user1);

        static::assertInstanceOf(Response::class, $response);
        static::assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        static::assertNotEmpty($content);
        static::assertInternalType('array', $content);

        $data = $content['resource']['data'];

        static::assertArrayHasKey('isLanguageInfoLooked', $data);
        static::assertArrayHasKey('areQuestionsLooked', $data);

        static::assertTrue($data['isLanguageInfoLooked']);
        static::assertFalse($data['areQuestionsLooked']);


        /** $language2 assertions */
        $this->assertLanguageRegistration($language2, $user1);
        $this->assertCurrentLearningUser($language2, $user1);

        $response = $this->learningUserController->getDynamicComponentsStatus($user1);

        static::assertInstanceOf(Response::class, $response);
        static::assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        static::assertNotEmpty($content);
        static::assertInternalType('array', $content);

        $data = $content['resource']['data'];

        static::assertArrayHasKey('isLanguageInfoLooked', $data);
        static::assertArrayHasKey('areQuestionsLooked', $data);

        static::assertFalse($data['isLanguageInfoLooked']);
        static::assertFalse($data['areQuestionsLooked']);

        $this->assertMarkLanguageInfo($user1);

        $response = $this->learningUserController->getDynamicComponentsStatus($user1);

        static::assertInstanceOf(Response::class, $response);
        static::assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        static::assertNotEmpty($content);
        static::assertInternalType('array', $content);

        $data = $content['resource']['data'];

        static::assertArrayHasKey('isLanguageInfoLooked', $data);
        static::assertArrayHasKey('areQuestionsLooked', $data);

        static::assertTrue($data['isLanguageInfoLooked']);
        static::assertFalse($data['areQuestionsLooked']);
    }

    public function test_questions()
    {
        $this->questionsDataProvider->createDefaultDb($this->getFaker());

        $response = $this->learningUserController->getQuestions();

        static::assertInstanceOf(Response::class, $response);
        static::assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        static::assertNotEmpty($content);
        static::assertInternalType('array', $content);

        static::assertNotEmpty($content['collection']);
        static::assertNotEmpty($content['collection']['data']);

        static::assertInternalType('array', $content['collection']['data']);

        $this->manualReset();
    }

    public function test_mark_questions_answered()
    {
        $this->manualReset();

        $answers = [
            SpeakingLanguagesType::getName() => 2,
            ProfessionType::getName() => 'arts_and_entertainment',
            PersonType::getName() => 'risk_taker',
            LearningTimeType::getName() => 'morning',
            FreeTimeType::getName() => '30_minutes',
            MemoryType::getName() => 'short_term',
            ChallengesType::getName() => 'likes_challenges',
            StressfulJobType::getName() => 'stressful_job'
        ];

        $questionAnswers = new QuestionAnswers($answers);

        $user1 = $this->userDataProvider->createDefaultDb($this->getFaker());
        $language1 = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language1);
        $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

        $this->assertLanguageRegistration($language1, $user1);

        $response = $this->learningUserController->markQuestionsAnswered($user1, $questionAnswers);

        static::assertInstanceOf(Response::class, $response);
        static::assertEquals(403, $response->getStatusCode());

        $data = json_decode($response->getContent(), true);

        static::assertFalse($data['resource']['data']['areQuestionsLooked']);

        $this->assertMarkLanguageInfo($user1);

        $response = $this->learningUserController->markQuestionsAnswered($user1, $questionAnswers);

        static::assertInstanceOf(Response::class, $response);
        static::assertEquals(201, $response->getStatusCode());

        static::assertFalse($data['resource']['data']['areQuestionsLooked']);
    }

    public function test_validate_success_question_answers()
    {
        $answers = [
            SpeakingLanguagesType::getName() => 2,
            ProfessionType::getName() => 'arts_and_entertainment',
            PersonType::getName() => 'risk_taker',
            LearningTimeType::getName() => 'morning',
            FreeTimeType::getName() => '30_minutes',
            MemoryType::getName() => 'short_term',
            ChallengesType::getName() => 'likes_challenges',
            StressfulJobType::getName() => 'stressful_job'
        ];

        $questionAnswers = new QuestionAnswers($answers);
        $questionAnswersValidator = new QuestionAnswersValidator($questionAnswers, new Questions());

        $questionAnswersValidator->validate();
    }

    public function test_validate_failed_question_answers()
    {
        $answers = [
            SpeakingLanguagesType::getName() => "invalid",
            ProfessionType::getName() => 'arts_and_entertainment',
            PersonType::getName() => 'risk_taker',
            LearningTimeType::getName() => 'morning',
            FreeTimeType::getName() => '30_minutes',
            MemoryType::getName() => 'short_term',
            ChallengesType::getName() => 'likes_challenges',
            StressfulJobType::getName() => 'stressful_job'
        ];

        $questionAnswers = new QuestionAnswers($answers);
        $questionAnswersValidator = new QuestionAnswersValidator($questionAnswers, new Questions());

        $enteredCustomValidationException = false;
        $enteredTypeException = false;
        $enteredAnswerException = false;

        try {
            $questionAnswersValidator->validate();
        } catch (\RuntimeException $e) {
            $enteredCustomValidationException = true;

            $message = $e->getMessage();

            static::assertEquals('Answer for type \'speaking_languages\' has to be an integer', $message);
        }

        $answers = [
            SpeakingLanguagesType::getName() => 2,
            ProfessionType::getName() => 'invalid',
            PersonType::getName() => 'risk_taker',
            LearningTimeType::getName() => 'morning',
            FreeTimeType::getName() => '30_minutes',
            MemoryType::getName() => 'short_term',
            ChallengesType::getName() => 'likes_challenges',
            StressfulJobType::getName() => 'stressful_job'
        ];

        $questionAnswers = new QuestionAnswers($answers);
        $questionAnswersValidator = new QuestionAnswersValidator($questionAnswers, new Questions());

        try {
            $questionAnswersValidator->validate();
        } catch (\RuntimeException $e) {
            $enteredAnswerException = true;

            $message = $e->getMessage();

            static::assertEquals('Invalid question answer \'invalid\' for type \'profession\'', $message);
        }

        $answers = [
            SpeakingLanguagesType::getName() => 2,
            'professionr' => 'arts_and_entertainment',
            PersonType::getName() => 'risk_taker',
            LearningTimeType::getName() => 'morning',
            FreeTimeType::getName() => '30_minutes',
            MemoryType::getName() => 'short_term',
            ChallengesType::getName() => 'likes_challenges',
            StressfulJobType::getName() => 'stressful_job',
        ];

        $questionAnswers = new QuestionAnswers($answers);
        $questionAnswersValidator = new QuestionAnswersValidator($questionAnswers, new Questions());

        try {
            $questionAnswersValidator->validate();
        } catch (\RuntimeException $e) {
            $enteredTypeException = true;

            $message = $e->getMessage();

            static::assertEquals('Invalid question type \'professionr\' given', $message);
        }

        static::assertTrue($enteredCustomValidationException);
        static::assertTrue($enteredAnswerException);
        static::assertTrue($enteredTypeException);
    }

    private function assertAlreadyLearningLanguages(array $languages, User $user)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy([
            'username' => $user->getUsername(),
        ]);

        $response = $this->languageController->getAll($user);
        $content = json_decode($response->getContent(), true);
        $collection = $content['collection']['data'];

        $collectionMetadata = [];
        foreach ($collection as $item) {
            if ($item['alreadyLearning'] === true) {
                $collectionMetadata[] = $item['id'];
            }
        }

        static::assertEquals(count($collectionMetadata), count($languages));

    }
    /**
     * @param User $user
     */
    private function assertLanguageInfoNotLooked(User $user)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy([
            'username' => $user->getUsername(),
        ]);

        $response = $this->learningUserController->isLanguageInfoLooked($user);

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(200, $response->getStatusCode());

        $response = json_decode($response->getContent(), true);

        static::assertNotEmpty($response);
        static::assertInternalType('array', $response);

        static::assertEquals('GET', $response['method']);

        $resource = $response['resource']['data'];

        static::assertFalse($resource['isLanguageInfoLooked']);

        $learningUserLanguage = $resource['language'];
        $currentLearningUser = $user->getCurrentLearningUser();

        static::assertEquals($learningUserLanguage['id'], $currentLearningUser->getLanguage()->getId());
        static::assertEquals($learningUserLanguage['name'], $currentLearningUser->getLanguage()->getName());
    }

    private function assertLanguageInfoLooked(User $user)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy([
            'username' => $user->getUsername(),
        ]);

        $response = $this->learningUserController->isLanguageInfoLooked($user);

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(200, $response->getStatusCode());

        $response = json_decode($response->getContent(), true);

        static::assertNotEmpty($response);
        static::assertInternalType('array', $response);

        static::assertEquals('GET', $response['method']);

        $resource = $response['resource']['data'];

        static::assertTrue($resource['isLanguageInfoLooked']);

        $learningUserLanguage = $resource['language'];
        $currentLearningUser = $user->getCurrentLearningUser();

        static::assertEquals($learningUserLanguage['id'], $currentLearningUser->getLanguage()->getId());
        static::assertEquals($learningUserLanguage['name'], $currentLearningUser->getLanguage()->getName());
    }
    /**
     * @param User $user
     */
    private function assertMarkLanguageInfo(User $user)
    {
        $response = $this->learningUserController->markLanguageInfoLooked($user);

        /** @var User $user */
        $user = $this->userRepository->findOneBy([
            'username' => $user->getUsername(),
        ]);

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(201, $response->getStatusCode());

        $response = json_decode($response->getContent(), true);

        static::assertNotEmpty($response);
        static::assertInternalType('array', $response);

        static::assertEquals('POST', $response['method']);

        $resource = $response['resource']['data'];
        $currentLearningUser = $user->getCurrentLearningUser();

        static::assertTrue($resource['isLanguageInfoLooked']);
        static::assertEquals($resource['isLanguageInfoLooked'], $currentLearningUser->getIsLanguageInfoLooked());

        $language = $resource['language'];

        static::assertEquals($language['id'], $currentLearningUser->getLanguage()->getId());
        static::assertEquals($language['name'], $currentLearningUser->getLanguage()->getName());
    }
    /**
     * @param Language $language
     * @param User $user
     */
    private function assertLanguageRegistration(Language $language, User $user)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy([
            'username' => $user->getUsername(),
        ]);

        $response = $this->learningUserController->registerLearningUser($language, $user);

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(201, $response->getStatusCode());

        $response = json_decode($response->getContent(), true);

        static::assertNotEmpty($response);
        static::assertInternalType('array', $response);

        static::assertEquals('POST', $response['method']);

        $resource = $response['resource']['data'];

        /** @var User $refreshedUser */
        $refreshedUser = $this->userRepository->findOneBy([
            'username' => $user->getUsername(),
        ]);

        static::assertEquals($resource['id'], $refreshedUser->getCurrentLearningUser()->getId());

        static::assertEquals($resource['language']['id'], $language->getId());
        static::assertEquals($resource['language']['name'], $language->getName());
    }
    /**
     * @param Language $language
     * @param User $user
     */
    public function assertLanguageRegistrationUpdate(Language $language, User $user)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy([
            'username' => $user->getUsername(),
        ]);

        $response = $this->learningUserController->registerLearningUser($language, $user);

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(200, $response->getStatusCode());

        $response = json_decode($response->getContent(), true);

        static::assertNotEmpty($response);
        static::assertInternalType('array', $response);

        static::assertEquals('POST', $response['method']);

        $resource = $response['resource']['data'];
        /** @var User $refreshedUser */
        $refreshedUser = $this->userRepository->findOneBy([
            'username' => $user->getUsername(),
        ]);

        static::assertEquals($resource['id'], $refreshedUser->getCurrentLearningUser()->getId());

        static::assertEquals($resource['language']['id'], $language->getId());
        static::assertEquals($resource['language']['name'], $language->getName());
    }
    /**
     * @param Language $language
     * @param User $user
     */
    private function assertCurrentLearningUser(Language $language, User $user)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy([
            'username' => $user->getUsername(),
        ]);

        $response = $this->learningUserController->getCurrentLearningUser($user);

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertEquals(200, $response->getStatusCode());

        $response = json_decode($response->getContent(), true);

        static::assertNotEmpty($response);
        static::assertInternalType('array', $response);

        static::assertEquals('GET', $response['method']);

        $resource = $response['resource']['data'];
        $currentLearningUser = $user->getCurrentLearningUser();

        static::assertEquals($resource['id'], $currentLearningUser->getId());
        static::assertEquals($resource['isLanguageInfoLooked'], $currentLearningUser->getIsLanguageInfoLooked());

        $learningUserLanguage = $resource['language'];

        static::assertEquals($language->getId(), $learningUserLanguage['id']);
        static::assertEquals($language->getName(), $learningUserLanguage['name']);
    }
}