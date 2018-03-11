<?php

namespace PublicApi;

use BlueDot\BlueDot;
use PublicApi\LearningUser\Business\Implementation\LearningMetadataImplementation;
use PublicApiBundle\Entity\LearningUser;
use TestLibrary\LanglandAdminTestCase;
use AdminBundle\Command\Helper\FakerTrait;
use PublicApi\LearningUser\Business\Implementation\LearningUserImplementation;
use TestLibrary\DataProvider\UserDataProvider;
use Tests\TestLibrary\DataProvider\CourseDataProvider;
use Tests\TestLibrary\DataProvider\LanguageDataProvider;
use TestLibrary\DataProvider\LearningUserDataProvider;
use Tests\TestLibrary\DataProvider\LessonDataProvider;

class LearningMetadataImplementationTest extends LanglandAdminTestCase
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
     * @var UserDataProvider $userDataProvider
     */
    private $userDataProvider;
    /**
     * @var LearningUserDataProvider $learningUserDataProvider
     */
    private $learningUserDataProvider;
    /**
     * @var CourseDataProvider $courseDataProvider
     */
    private $courseDataProvider;
    /**
     * @var LessonDataProvider $lessonDataProvider
     */
    private $lessonDataProvider;
    /**
     * @var LearningMetadataImplementation $learningMetadataImplementation
     */
    private $learningMetadataImplementation;
    /**
     * @var BlueDot
     */
    private $blueDot;

    public function setUp()
    {
        parent::setUp();

        $this->languageDataProvider = $this->container->get('langland.data_provider.language');
        $this->learningUserImplementation = $this->container->get('langland.public_api.business.implementation.learning_user');
        $this->userDataProvider = $this->container->get('langland.data_provider.user');
        $this->learningUserDataProvider = $this->container->get('langland.data_provider.learning_user');
        $this->lessonDataProvider = $this->container->get('langland.data_provider.lesson');
        $this->courseDataProvider = $this->container->get('langland.data_provider.course');
        $this->blueDot = $this->container->get('langland.common.blue_dot');
        $this->learningMetadataImplementation = $this->container->get('langland.public_api.business.implementation.learning_metadata');
    }

    public function test_first_learning_metadata_creation()
    {
        $dataCollectorPromise = $this->blueDot
            ->createStatementBuilder()
            ->addSql('SELECT * from data_collector')
            ->execute();

        $learningLessonPromise = $this->blueDot
            ->createStatementBuilder()
            ->addSql('SELECT * FROM learning_lessons')
            ->execute();

        $learningMetadataPromise = $this->blueDot
            ->createStatementBuilder()
            ->addSql('SELECT * FROM learning_metadata')
            ->execute();

        static::assertTrue($dataCollectorPromise->isFailure());
        static::assertTrue($learningLessonPromise->isFailure());
        static::assertTrue($learningMetadataPromise->isFailure());

        $language1 = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language1);
        $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

        $user = $this->userDataProvider->createDefaultDb($this->getFaker());

        /** @var LearningUser $learningUser1 */
        $learningUser1 = $this->learningUserImplementation->registerLearningUser($language1, $user)['learningUser'];

        $this->learningMetadataImplementation->createFirstLearningMetadata($learningUser1);

        $dataCollectorPromise = $this->blueDot
            ->createStatementBuilder()
            ->addSql('SELECT * from data_collector')
            ->execute();

        $learningLessonPromise = $this->blueDot
            ->createStatementBuilder()
            ->addSql('SELECT * FROM learning_lessons')
            ->execute();

        $learningMetadataPromise = $this->blueDot
            ->createStatementBuilder()
            ->addSql('SELECT * FROM learning_metadata')
            ->execute();

        static::assertTrue($dataCollectorPromise->isSuccess());
        static::assertTrue($learningLessonPromise->isSuccess());
        static::assertTrue($learningMetadataPromise->isSuccess());

        $language2 = $this->languageDataProvider->createDefaultDb($this->getFaker());

        $course = $this->courseDataProvider->createDefaultDb($this->getFaker(), $language2);
        $this->lessonDataProvider->createDefaultDb($this->getFaker(), $course);

        $user = $this->userDataProvider->createDefaultDb($this->getFaker());

        /** @var LearningUser $learningUser2 */
        $learningUser2 = $this->learningUserImplementation->registerLearningUser($language2, $user)['learningUser'];

        $this->learningMetadataImplementation->createFirstLearningMetadata($learningUser2);

        $dataCollectorPromise = $this->blueDot
            ->createStatementBuilder()
            ->addSql('SELECT COUNT(id) AS id from data_collector')
            ->execute();

        $learningLessonPromise = $this->blueDot
            ->createStatementBuilder()
            ->addSql('SELECT COUNT(id) AS id FROM learning_lessons')
            ->execute();

        $learningMetadataPromise = $this->blueDot
            ->createStatementBuilder()
            ->addSql('SELECT COUNT(id) AS id FROM learning_metadata')
            ->execute();

        static::assertTrue($dataCollectorPromise->isSuccess());
        static::assertTrue($learningLessonPromise->isSuccess());
        static::assertTrue($learningMetadataPromise->isSuccess());

        static::assertEquals(2, $dataCollectorPromise->getResult()->normalizeIfOneExists()->get('id'));
        static::assertEquals(2, $learningLessonPromise->getResult()->normalizeIfOneExists()->get('id'));
        static::assertEquals(2, $learningMetadataPromise->getResult()->normalizeIfOneExists()->get('id'));

        $learningMetadataPromise = $this->blueDot
            ->createStatementBuilder()
            ->addSql('SELECT * FROM learning_metadata WHERE learning_user_id = :learning_user_id')
            ->addParameter('learning_user_id', $learningUser1->getId())
            ->execute();

        static::assertTrue($learningMetadataPromise->isSuccess());
        static::assertEquals(1, count($learningMetadataPromise->getResult()));

        $learningMetadataPromise = $this->blueDot
            ->createStatementBuilder()
            ->addSql('SELECT * FROM learning_metadata WHERE learning_user_id = :learning_user_id')
            ->addParameter('learning_user_id', $learningUser2->getId())
            ->execute();

        static::assertTrue($learningMetadataPromise->isSuccess());
        static::assertEquals(1, count($learningMetadataPromise->getResult()));

        $learningMetadataPromise = $this->blueDot
            ->createStatementBuilder()
            ->addSql('SELECT learning_user_id FROM learning_metadata WHERE learning_user_id = :learning_user_id')
            ->addParameter('learning_user_id', $learningUser1->getId())
            ->execute();

        static::assertTrue($learningMetadataPromise->isSuccess());
        static::assertEquals($learningUser1->getId(), $learningMetadataPromise->getResult()->normalizeIfOneExists()->get('learning_user_id'));

        $learningMetadataPromise = $this->blueDot
            ->createStatementBuilder()
            ->addSql('SELECT learning_user_id FROM learning_metadata WHERE learning_user_id = :learning_user_id')
            ->addParameter('learning_user_id', $learningUser2->getId())
            ->execute();

        static::assertTrue($learningMetadataPromise->isSuccess());
        static::assertEquals($learningUser2->getId(), $learningMetadataPromise->getResult()->normalizeIfOneExists()->get('learning_user_id'));
    }
}