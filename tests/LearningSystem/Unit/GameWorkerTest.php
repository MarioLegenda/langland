<?php

namespace LearningSystem\Unit;

use AdminBundle\Command\Helper\FakerTrait;
use ArmorBundle\Entity\User;
use LearningSystem\Infrastructure\Type\ChallengesType;
use LearningSystem\Infrastructure\Type\FreeTimeType;
use LearningSystem\Infrastructure\Type\GameType\BasicGameType;
use LearningSystem\Infrastructure\Type\LearningTimeType;
use LearningSystem\Infrastructure\Type\MemoryType;
use LearningSystem\Infrastructure\Type\PersonType;
use LearningSystem\Infrastructure\Type\ProfessionType;
use LearningSystem\Infrastructure\Type\SpeakingLanguagesType;
use LearningSystem\Infrastructure\Type\StressfulJobType;
use LearningSystem\Library\Game\Implementation\GameInterface;
use LearningSystem\Library\ProvidedDataInterface;
use LearningSystem\Library\Worker\GameWorker;
use PublicApi\LearningSystem\DataProvider\Word\ProvidedWord;
use PublicApi\LearningSystem\DataProvider\Word\ProvidedWordDataCollection;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use TestLibrary\DataProvider\LearningUserDataProvider;
use TestLibrary\DataProvider\UserDataProvider;
use TestLibrary\DataProvider\WordDataProvider;
use TestLibrary\PublicApiTestCase;
use Tests\TestLibrary\DataProvider\CourseDataProvider;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;
use Tests\TestLibrary\DataProvider\LessonDataProvider;

class GameWorkerTest extends PublicApiTestCase
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

        $prepared = $this->prepareFullLearningMetadata([1, 2, 3, 4, 5]);

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

    public function test_InitialDataDecider()
    {
        $this->manualReset();

        $questionAnswers = array(
            SpeakingLanguagesType::getName() => 0,
            ProfessionType::getName() => 'arts_and_entertainment',
            PersonType::getName() => 'sure_thing',
            LearningTimeType::getName() => 'morning',
            FreeTimeType::getName() => '30_minutes',
            MemoryType::getName() => 'short_term',
            ChallengesType::getName() => 'dislike_challenges',
            StressfulJobType::getName() => 'stressful_job',
        );

        $numberOfLanguages = 3;
        $wordLevels = [1, 2, 3];

        for ($i = 0; $i < $numberOfLanguages; $i++) {
            $language = $this->prepareLanguageData($wordLevels[$i]);
            $userData = $this->prepareUserData($language, $questionAnswers);

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

            $wordNumbers = 3;

            for ($i = 0; $i < $wordNumbers; $i++) {
                $this->mockInitialWordDataProvider(
                    $mockedProviderData['learningUserProvider'],
                    $mockedProviderData['languageProvider']
                );

                $initialDataDecider = $this->container->get('public_api.learning_system.initial_data_decider');

                $decidedData = $initialDataDecider->getData();

                static::assertEquals(BasicGameType::getName(), $decidedData['game_type']);

                $data = $decidedData['data'];

                static::assertInstanceOf(ProvidedWordDataCollection::class, $data);

                static::assertEquals(15, count($data));
            }
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

            $fields = $providedWord->getFields();

            static::assertNotEmpty($fields);

            foreach ($fields as $field) {
                static::assertTrue($providedWord->hasField($field));
                static::assertNotEmpty($providedWord->getField($field));
            }

            static::assertEquals($wordLevel, $providedWord->getField('level'));
        }
    }
}