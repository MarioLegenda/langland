<?php

namespace LearningSystem\Unit;

use AdminBundle\Command\Helper\FakerTrait;
use AdminBundle\Entity\Language;
use AdminBundle\Entity\Lesson;
use ArmorBundle\Entity\User;
use LearningSystem\Library\Game\Implementation\GameInterface;
use LearningSystem\Library\ProvidedDataInterface;
use LearningSystem\Library\Worker\GameWorker;
use PublicApi\Language\Infrastructure\LanguageProvider;
use PublicApi\LearningSystem\DataProvider\InitialWordDataProvider;
use PublicApi\LearningSystem\DataProvider\Word\ProvidedWord;
use PublicApi\LearningSystem\DataProvider\Word\ProvidedWordDataCollection;
use PublicApi\LearningSystem\QuestionAnswersApplicationProvider;
use PublicApi\LearningUser\Business\Implementation\LearningMetadataImplementation;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use TestLibrary\DataProvider\LearningUserDataProvider;
use TestLibrary\DataProvider\UserDataProvider;
use TestLibrary\DataProvider\WordDataProvider;
use TestLibrary\LanglandAdminTestCase;
use Tests\TestLibrary\DataProvider\CourseDataProvider;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;
use Tests\TestLibrary\DataProvider\LessonDataProvider;

class GameWorkerTest extends LanglandAdminTestCase
{
    use FakerTrait;
    /**
     * @var LanguageDataProvider $languageDataProvider
     */
    private $languageDataProvider;
    /**
     * @var UserDataProvider $userDataProvider
     */
    private $userDataProvider;
    /**
     * @var LessonDataProvider $lessonDataProvider
     */
    private $lessonDataProvider;
    /**
     * @var CourseDataProvider $courseDataProvider
     */
    private $courseDataProvider;
    /**
     * @var LearningUserDataProvider $learningUserDataProvider
     */
    private $learningUserDataProvider;
    /**
     * @var WordDataProvider $wordDataProvider
     */
    private $wordDataProvider;
    /**
     * @var GameWorker $gameWorker
     */
    private $gameWorker;

    public function setUp()
    {
        parent::setUp();

        $this->languageDataProvider = $this->container->get('data_provider.language');
        $this->userDataProvider = $this->container->get('data_provider.user');
        $this->lessonDataProvider = $this->container->get('data_provider.lesson');
        $this->courseDataProvider = $this->container->get('data_provider.course');
        $this->learningUserDataProvider = $this->container->get('data_provider.learning_user');
        $this->wordDataProvider = $this->container->get('data_provider.word');
    }

    public function test_GameWorker()
    {
        $this->manualReset();

        $prepared = $this->prepareFullLearningMetadata(1);

        $this->createWords($prepared['language'],100, [
            'level' => 1,
        ]);

        /** @var GameWorker $gameWorker */
        $gameWorker = $this->container->get('learning_system.game_worker.initial_system_game_worker');

        /** @var GameInterface $game */
        $game = $gameWorker->createGame();

        static::assertInstanceOf(GameInterface::class, $game);
        static::assertInstanceOf(ProvidedDataInterface::class, $game->getGameData());
    }

    public function test_InitialWordDataProvider()
    {
        $this->manualReset();

        $prepared = $this->prepareFullLearningMetadata(1);

        $levels = [1, 2, 3, 4];

        foreach ($levels as $level) {
            $this->createWords($prepared['language'],50, [
                'level' => $level,
            ]);
        }

        $initialWordDataProvider = $this->container->get('public_api.learning_system.data_provider.word_data_provider');

        $wordNumbers = [15, 17, 20, 35];

        foreach ($wordNumbers as $wordNumber) {
            $wordLevel = rand(1, 4);

            /** @var ProvidedWordDataCollection $providedData */
            $providedData = $initialWordDataProvider->getData([
                'word_number' => $wordNumber,
                'word_level' => $wordLevel,
            ]);

            $this->assertRawProvidedWordCollection($providedData, $wordNumber, $wordLevel);
        }
    }

    public function test_InitialWordDataProvider_MultipleLanguages()
    {
        $this->manualReset();

        $numberOfLanguages = 3;
        $wordLevels = [1, 2, 3];

        for ($i = 0; $i < $numberOfLanguages; $i++) {
            $language = $this->prepareLanguageData($wordLevels[$i]);
            $userData = $this->prepareUserData($language);

            /** @var LearningUser $learningUser */
            $learningUser = $userData['learningUser'];
            /** @var User $user */
            $user = $userData['user'];

            $mockedProviderData = $this->mockLearningUserProvider($user);

            $this->prepareLearningMetadata($learningUser);

            foreach ($wordLevels as $level) {
                $this->createWords($language,50, [
                    'level' => $level,
                ]);
            }

            $wordNumbers = [15, 17, 20];

            foreach ($wordNumbers as $wordNumber) {
                $wordLevel = $wordLevels[array_rand($wordLevels)];

                $initialWordDataProvider = $this->mockInitialWordDataProvider(
                    $mockedProviderData['learningUserProvider'],
                    $mockedProviderData['languageProvider']
                );

                /** @var ProvidedWordDataCollection $providedData */
                $providedData = $initialWordDataProvider->getData([
                    'word_number' => $wordNumber,
                    'word_level' => $wordLevel,
                ]);

                $this->assertRawProvidedWordCollection($providedData, $wordNumber, $wordLevel);
            }
        }
    }

    public function test_InitialWordDataProvider_Controller()
    {
        $this->manualReset();

        $numberOfLanguages = 3;
        $wordLevels = [1, 2, 3];

        for ($i = 0; $i < $numberOfLanguages; $i++) {
            $language = $this->prepareLanguageData($wordLevels[$i]);
            $userData = $this->prepareUserData($language);

            /** @var LearningUser $learningUser */
            $learningUser = $userData['learningUser'];
            /** @var User $user */
            $user = $userData['user'];

            $mockedProviderData = $this->mockLearningUserProvider($user);

            $this->prepareLearningMetadata($learningUser);

            foreach ($wordLevels as $level) {
                $this->createWords($language,50, [
                    'level' => $level,
                ]);
            }

            $wordNumbers = [15, 17, 20];

            foreach ($wordNumbers as $wordNumber) {
                $this->mockInitialWordDataProvider(
                    $mockedProviderData['learningUserProvider'],
                    $mockedProviderData['languageProvider']
                );

                $controller = $this->container->get('learning_system.business.controller.initial_data_creation');

                $response = $controller->makeInitialDataCreation();

                static::assertInstanceOf(JsonResponse::class, $response);
                static::assertEquals(201, $response->getStatusCode());
            }
        }
    }
    /**
     * @param int $wordLevel
     * @return Language
     */
    private function prepareLanguageData(int $wordLevel): Language
    {
        $language = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language);
        $lesson = $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

        $this->createWordsForLesson($lesson, $language, 5, [
            'level' => $wordLevel,
        ]);

        return $language;
    }
    /**
     * @param Language $language
     * @return array
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function prepareUserData(Language $language): array
    {
        $learningUser = $this->learningUserDataProvider->createDefaultDb($this->getFaker(), $language);
        $user = $this->userDataProvider->createDefaultDb($this->getFaker());
        $user->setCurrentLearningUser($learningUser);

        return [
            'learningUser' => $learningUser,
            'user' => $user,
        ];
    }
    /**
     * @param int $wordLevel
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function prepareFullLearningMetadata(int $wordLevel): array
    {
        $language = $this->prepareLanguageData($wordLevel);

        $userData = $this->prepareUserData($language);

        /** @var User $user */
        $user = $userData['user'];
        /** @var LearningUser $learningUser */
        $learningUser = $userData['learningUser'];

        $this->mockLearningUserProvider($user);

        $this->prepareLearningMetadata($learningUser);

        return [
            'language' => $language,
        ];
    }
    /**
     * @param LearningUser $learningUser
     */
    private function prepareLearningMetadata(LearningUser $learningUser)
    {
        /** @var LearningMetadataImplementation $learningMetadataImplementation */
        $learningMetadataImplementation = $this->container->get('public_api.business.implementation.learning_metadata');

        $learningMetadataImplementation->createFirstLearningMetadata($learningUser);
    }
    /**
     * @param User $user
     * @return array
     */
    private function mockLearningUserProvider(User $user): array
    {
        $token = new UsernamePasswordToken($user, 'root', 'admin', ['ROLE_PUBLIC_API_USER']);
        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken($token);

        $learningUserProvider = new LearningUserProvider($tokenStorage);
        $languageProvider = new LanguageProvider($learningUserProvider);
        $questionAnswersProvider = new QuestionAnswersApplicationProvider($learningUserProvider);

        $this->container->set('public_api.provider.language', $languageProvider);
        $this->container->set('public_api.learning_user_provider', $learningUserProvider);
        $this->container->set('public_api.provider.question_answers_application_provider', $questionAnswersProvider);

        return [
            'languageProvider' => $languageProvider,
            'learningUserProvider' => $learningUserProvider,
        ];
    }
    /**
     * @param LearningUserProvider $learningUserProvider
     * @param LanguageProvider $languageProvider
     * @return InitialWordDataProvider
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     */
    private function mockInitialWordDataProvider(
        LearningUserProvider $learningUserProvider,
        LanguageProvider $languageProvider
    ): InitialWordDataProvider {
        $apiName = 'learning_user_metadata';
        $blueDot = $this->container->get('common.blue_dot');
        $blueDot->useRepository($apiName);

        return new InitialWordDataProvider(
            $blueDot,
            $apiName,
            $languageProvider,
            $learningUserProvider
        );
    }
    /**
     * @param Lesson $lesson
     * @param Language $language
     * @param int $numOfWords
     * @param array|null $seedData
     */
    private function createWordsForLesson(
        Lesson $lesson,
        Language $language,
        int $numOfWords = 5,
        array $seedData = null
    ) {
        for ($i = 0; $i < $numOfWords; $i++) {
            $this->wordDataProvider->createWithLesson(
                $this->getFaker(),
                $language,
                $lesson,
                $seedData
            );
        }
    }
    /**
     * @param Language $language
     * @param int $numOfWords
     * @param array|null $seedData
     */
    private function createWords(Language $language, int $numOfWords, array $seedData = null)
    {
        for ($i = 0; $i < $numOfWords; $i++) {
            $this->wordDataProvider->createDefaultDb($this->getFaker(), $language, $seedData);
        }
    }
    /**
     * @param ProvidedWordDataCollection $providedWordDataCollection
     * @param int $wordNumber
     * @param int $wordLevel
     */
    private function assertRawProvidedWordCollection(
        ProvidedWordDataCollection $providedWordDataCollection,
        int $wordNumber,
        int $wordLevel
    ) {
        static::assertInstanceOf(ProvidedWordDataCollection::class, $providedWordDataCollection);
        static::assertEquals($wordNumber, count($providedWordDataCollection));

        /** @var ProvidedWord $providedWord */
        foreach ($providedWordDataCollection as $providedWord) {
            static::assertInstanceOf(ProvidedWord::class, $providedWord);

            static::assertInternalType('array', $providedWord->getTranslations());
            static::assertNotEmpty($providedWord->getFalseTranslations());

            static::assertInternalType('array', $providedWord->getFalseTranslations());
            static::assertNotEmpty($providedWord->getFalseTranslations());

            $fields = $providedWord->getFields([
                'false_translations',
                'translations',
            ]);

            static::assertNotEmpty($fields);
            static::assertArrayNotHasKey('false_translations', $fields);
            static::assertArrayNotHasKey('translations', $fields);

            foreach ($fields as $field) {
                static::assertTrue($providedWord->hasField($field));
                static::assertNotEmpty($providedWord->getField($field));
            }

            $fields = $providedWord->getFields();

            static::assertNotEmpty($fields);
            static::assertArrayNotHasKey('false_translations', $fields);
            static::assertArrayNotHasKey('translations', $fields);

            foreach ($fields as $field) {
                static::assertTrue($providedWord->hasField($field));
                static::assertNotEmpty($providedWord->getField($field));
            }

            static::assertEquals($wordLevel, $providedWord->getField('level'));
        }
    }
}