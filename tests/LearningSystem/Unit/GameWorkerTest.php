<?php

namespace LearningSystem\Unit;

use AdminBundle\Command\Helper\FakerTrait;
use AdminBundle\Entity\Language;
use AdminBundle\Entity\Lesson;
use ArmorBundle\Entity\User;
use LearningSystem\Library\Game\Implementation\GameInterface;
use LearningSystem\Library\Worker\GameWorker;
use PublicApi\LearningSystem\QuestionAnswersApplicationProvider;
use PublicApi\LearningUser\Business\Implementation\LearningMetadataImplementation;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
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

    public function test_game_worker()
    {
        $prepared = $this->prepareTest();

        $this->createWords($prepared['language'],100, [
            'level' => 1,
        ]);

        /** @var GameWorker $gameWorker */
        $gameWorker = $this->container->get('learning_system.game_worker.initial_system_game_worker');

        $game = $gameWorker->createGame();

        static::assertInstanceOf(GameInterface::class, $game);
    }
    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function prepareTest(): array
    {
        $language = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language);
        $lesson = $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

        $this->createWordsForLesson($lesson, $language, 5, [
            'level' => 1,
        ]);

        $learningUser = $this->learningUserDataProvider->createDefaultDb($this->getFaker(), $language);
        $user = $this->userDataProvider->createDefaultDb($this->getFaker());
        $user->setCurrentLearningUser($learningUser);

        $this->mockLearningUserProvider($user);

        /** @var LearningMetadataImplementation $learningMetadataImplementation */
        $learningMetadataImplementation = $this->container->get('public_api.business.implementation.learning_metadata');

        $learningMetadataImplementation->createFirstLearningMetadata($learningUser);

        return [
            'language' => $language,
        ];
    }
    /**
     * @param User $user
     */
    private function mockLearningUserProvider(User $user)
    {
        $token = new UsernamePasswordToken($user, 'root', 'admin', ['ROLE_PUBLIC_API_USER']);
        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken($token);

        $learningUserProvider = new LearningUserProvider($tokenStorage);
        $questionAnswersProvider = new QuestionAnswersApplicationProvider($learningUserProvider);

        $this->container->set('public_api.learning_user_provider', $learningUserProvider);
        $this->container->set('public_api.provider.question_answers_application_provider', $questionAnswersProvider);
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
        int $numOfWords,
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
}