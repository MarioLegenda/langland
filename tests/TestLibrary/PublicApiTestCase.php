<?php

namespace TestLibrary;

use AdminBundle\Entity\Language;
use AdminBundle\Command\Helper\FakerTrait;
use AdminBundle\Entity\Lesson;
use ArmorBundle\Entity\User;
use PublicApi\Infrastructure\Type\CourseType;
use PublicApi\Language\Infrastructure\LanguageProvider;
use PublicApi\LearningSystem\QuestionAnswersApplicationProvider;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use TestLibrary\DataProvider\LearningUserDataProvider;
use TestLibrary\DataProvider\UserDataProvider;
use TestLibrary\DataProvider\WordDataProvider;
use Tests\TestLibrary\DataProvider\CourseDataProvider;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;
use Tests\TestLibrary\DataProvider\LessonDataProvider;
use PublicApi\LearningSystem\Infrastructure\DataProvider\InitialWordDataProvider;
use PublicApi\LearningSystem\Business\Implementation\LearningMetadataImplementation;

class PublicApiTestCase extends LanglandAdminTestCase
{
    use FakerTrait;
    /**
     * @var LanguageDataProvider $languageDataProvider
     */
    protected $languageDataProvider;
    /**
     * @var UserDataProvider $userDataProvider
     */
    protected $userDataProvider;
    /**
     * @var LessonDataProvider $lessonDataProvider
     */
    protected $lessonDataProvider;
    /**
     * @var CourseDataProvider $courseDataProvider
     */
    protected $courseDataProvider;
    /**
     * @var LearningUserDataProvider $learningUserDataProvider
     */
    protected $learningUserDataProvider;
    /**
     * @var WordDataProvider $wordDataProvider
     */
    protected $wordDataProvider;

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
    /**
     * @param Language $language
     * @param array|null $courseSeedData
     * @param array|null $lessonSeedData
     * @return array
     */
    protected function prepareLanguageData(
        Language $language,
        array $courseSeedData = null,
        array $lessonSeedData = null
    ): array {
        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language, $courseSeedData);
        $lesson = $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course, $lessonSeedData);

        return [
            'language' => $language,
            'course' => $course,
            'lesson' => $lesson,
        ];
    }
    /**
     * @param Language $language
     * @param array|null $questionAnswers
     * @return array
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function prepareUserData(Language $language, array $questionAnswers = null): array
    {
        $learningUser = $this->learningUserDataProvider->createDefaultDb($this->getFaker(), $language, $questionAnswers);
        $user = $this->userDataProvider->createDefaultDb($this->getFaker());
        $user->setCurrentLearningUser($learningUser);

        $this->userDataProvider->getRepository()->persistAndFlush($user);

        return [
            'learningUser' => $learningUser,
            'user' => $user,
        ];
    }
    /**
     * @param int $numOfLearningUsers
     * @param CourseType|null $courseType
     * @param int $courseOrder
     * @param int $lessonOrder
     * @param int $level
     * @return array
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function prepareFullLearningMetadata(
        int $numOfLearningUsers = 4,
        CourseType $courseType = null,
        int $courseOrder = 0,
        int $lessonOrder = 0,
        int $level = 1
    ): array {
        $preparedLanguageData = $this->prepareLanguageData();

        $user = $this->userDataProvider->createDefaultDb($this->getFaker());

        $fullData = [];

        for ($i = 0; $i < $numOfLearningUsers; $i++) {
            $oneLearningUserData = $this->createOneLearningUserData(
                $user,
                $preparedLanguageData,
                $courseType,
                $courseOrder,
                $lessonOrder
            );

            $fullData[] = $oneLearningUserData;
        }

        return $fullData;
    }
    /**
     * @param User $user
     * @param array $languageData
     * @param CourseType|null $courseType
     * @param int $courseOrder
     * @param int $lessonOrder
     * @return array
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function createOneLearningUserData(
        User $user,
        array $languageData,
        CourseType $courseType = null,
        int $courseOrder,
        int $lessonOrder
    ): array {
        $language = $languageData['language'];

        $learningUser = $this->learningUserDataProvider->createDefaultDb($this->getFaker(), $language);

        $user->setCurrentLearningUser($learningUser);

        $this->userDataProvider->getRepository()->persistAndFlush($user);

        $this->mockProviders($user);

        $courseType = (!$courseType instanceof CourseType) ? CourseType::fromValue('Beginner'): $courseType;

        /** @var LearningMetadataImplementation $learningMetadataImplementation */
        $learningMetadataImplementation = $this->container->get('public_api.business.implementation.learning_metadata');

        $learningMetadata = $learningMetadataImplementation->createLearningMetadata(
            $courseType,
            $courseOrder,
            $lessonOrder
        );

        return array_merge([
            'learningMetadataId' => $learningMetadata['learningMetadataId'],
            'learningUser' => $learningUser,
            'user' => $user,
        ], $languageData);
    }
    /**
     * @param User $user
     * @return array
     */
    protected function mockProviders(User $user): array
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
            'questionAnswersProvider' => $questionAnswersProvider,
        ];
    }
    /**
     * @param Lesson $lesson
     * @param Language $language
     * @param int $numOfWords
     * @param array|null $seedData
     */
    protected function createWordsForLesson(
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
    protected function createWords(Language $language, int $numOfWords, array $seedData = null)
    {
        for ($i = 0; $i < $numOfWords; $i++) {
            $this->wordDataProvider->createDefaultDb($this->getFaker(), $language, $seedData);
        }
    }
    /**
     * @param LearningUserProvider $learningUserProvider
     * @param LanguageProvider $languageProvider
     * @return InitialWordDataProvider
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     */
    protected function mockInitialWordDataProvider(
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
}