<?php

namespace PublicApi\LearningSystem\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Library\Infrastructure\Repository\CommonRepository;
use PublicApi\Infrastructure\Communication\RepositoryCommunicator;
use PublicApiBundle\Entity\LearningLesson;
use PublicApiBundle\Entity\LearningMetadata;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\Routing\Router;
use PublicApiBundle\Entity\DataCollector;
use PublicApi\Infrastructure\Model\Lesson;
use PublicApi\Infrastructure\Model\Language;

class LearningMetadataRepository extends CommonRepository
{
    /**
     * @var Router $router
     */
    private $router;
    /**
     * @var RepositoryCommunicator $repositoryCommunicator
     */
    private $repositoryCommunicator;
    /**
     * @var LearningLessonRepository $learningLessonRepository
     */
    private $learningLessonRepository;
    /**
     * @var DataCollectorRepository $dataCollectorRepository
     */
    private $dataCollectorRepository;
    /**
     * LearningMetadataRepository constructor.
     * @param EntityManagerInterface $em
     * @param string $class
     * @param Router $router
     * @param RepositoryCommunicator $repositoryCommunicator
     * @param LearningLessonRepository $learningLessonRepository
     * @param DataCollectorRepository $dataCollectorRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        string $class,
        Router $router,
        RepositoryCommunicator $repositoryCommunicator,
        LearningLessonRepository $learningLessonRepository,
        DataCollectorRepository $dataCollectorRepository
    ) {
        parent::__construct($em, $class);

        $this->router = $router;
        $this->repositoryCommunicator = $repositoryCommunicator;
        $this->learningLessonRepository = $learningLessonRepository;
        $this->dataCollectorRepository = $dataCollectorRepository;
    }
    /**
     * @param LearningMetadata $learningMetadata
     * @return LearningMetadata
     */
    public function persistAndFlush(LearningMetadata $learningMetadata)
    {
        $this->em->persist($learningMetadata);
        $this->em->flush();

        return $learningMetadata;
    }
    /**
     * @param Language $language
     * @param LearningUser $learningUser
     * @return LearningLesson
     */
    public function createAllLearningLessonsForLearningUser(
        Language $language,
        LearningUser $learningUser
    ): LearningLesson {

        $lessons = $this->repositoryCommunicator->getLessonsByLanguage($language);

        $isFirst = true;
        $firstLearningLesson = null;
        /** @var Lesson $lesson */
        foreach ($lessons as $lesson) {
            $isAvailable = false;

            if ($isFirst === true) {
                $isAvailable = true;

                $isFirst = false;
            }

            $learningMetadataDataCollector = new DataCollector(false, 0, 0, 0, 0);
            $learningLessonDataCollector = new DataCollector(false, 0, 0, 0, 0);
            $learningMetadata = new LearningMetadata($learningMetadataDataCollector);

            $this->dataCollectorRepository->persist($learningMetadataDataCollector);
            $this->dataCollectorRepository->persist($learningLessonDataCollector);
            $this->persist($learningMetadata);

            $learningLesson = new LearningLesson(
                $learningUser,
                $lesson,
                $learningMetadata,
                false,
                $isAvailable
            );

            $this->learningLessonRepository->persist($learningLesson);

            if (!$firstLearningLesson instanceof LearningLesson) {
                $firstLearningLesson = $learningLesson;
            }
        }

        $this->flush();

        return $firstLearningLesson;
    }
    /**
     * @param LearningUser $learningUser
     * @param Language $language
     * @return array
     */
    public function getLearningLessonPresentation(
        LearningUser $learningUser,
        Language $language
    ): array {
        $learningLessons = $this->repositoryCommunicator->getAllLearningLessonsByLearningUser(
            $learningUser
        );


    }
    /**
     * @param int $learningUserId
     * @param int $languageId
     * @return array
     */
    public function getLearningGamesPresentation(
        int $learningUserId,
        int $languageId
    ): array {
        $this->blueDot->useRepository('presentation');

        return $this->blueDot->execute('service.learning_games_presentation', [
            'learning_user_id' => $learningUserId,
            'language_id' => $languageId,
        ])->getResult()['data'];
    }
    /**
     * @param int $learningLessonId
     * @throws \BlueDot\Exception\ConfigurationException
     * @throws \BlueDot\Exception\ConnectionException
     * @throws \BlueDot\Exception\RepositoryException
     * @return array
     */
    public function getLearningLessonById(int $learningLessonId): array
    {
        $this->blueDot->useRepository('public_api_lesson');

        $data = $this->blueDot->execute('simple.select.find_learning_lesson_by_id', [
            'learning_lesson_id' => $learningLessonId,
        ])->getResult()['data'];

        $lessonName = \URLify::filter(json_decode($data['json_lesson'], true)['name']);

        $data['urls'] = [
            'backend_url' => $this->router->generate('get_learning_lesson_by_id', [
                'id' => $data['id'],
            ]),
            'frontend_url' => sprintf('langland/lesson/%s/%d', $lessonName, $data['learning_lesson_id'])
        ];

        return $data;
    }
    /**
     * @param int $learningMetadataId
     * @return array
     */
    public function getRunnableGameByLearningMetadataId(int $learningMetadataId): array
    {
        $this->blueDot->useRepository('public_api_game');

        $this->blueDot->execute('service.create_runnable_game', [
            'learning_metadata_id' => $learningMetadataId,
        ])->getResult()['data'];
    }
}