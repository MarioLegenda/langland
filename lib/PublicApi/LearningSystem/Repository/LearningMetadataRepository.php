<?php

namespace PublicApi\LearningSystem\Repository;

use BlueDot\BlueDot;
use Library\Infrastructure\BlueDot\BaseBlueDotRepository;
use Symfony\Component\Routing\Router;

class LearningMetadataRepository extends BaseBlueDotRepository
{
    /**
     * @var Router $router
     */
    private $router;
    /**
     * LearningMetadataRepository constructor.
     * @param Router $router
     * @param BlueDot $blueDot
     * @param string $apiName
     */
    public function __construct(
        Router $router,
        BlueDot $blueDot,
        string $apiName
    ) {
        parent::__construct($blueDot, $apiName);

        $this->router = $router;
    }

    /**
     * @param int $languageId
     * @param int $learningUserId
     * @return array
     */
    public function createLearningMetadata(
        int $languageId,
        int $learningUserId
    ): array {
        $this->blueDot->useRepository($this->apiName);

        return $this->doCreateLearningMetadata(
            $languageId,
            $learningUserId
        );
    }
    /**
     * @param int $learningUserId
     * @param int $languageId
     * @return array
     */
    private function doCreateLearningMetadata(
        int $languageId,
        int $learningUserId
    ): array {
        return $this->blueDot->execute('service.create_learning_metadata', [
            'language_id' => $languageId,
            'learning_user_id' => $learningUserId,
        ])->getResult()['data'];
    }
    /**
     * @param int $learningUserId
     * @param int $languageId
     * @return array
     */
    public function getLearningLessonPresentation(
        int $learningUserId,
        int $languageId
    ): array {
        if (!$this->blueDot->repository()->isCurrentlyUsingRepository('presentation')) {
            $this->blueDot->useRepository('presentation');
        }

        return $this->blueDot->execute('service.learning_lesson_presentation', [
            'learning_user_id' => $learningUserId,
            'language_id' => $languageId,
            'router' => $this->router,
        ])->getResult()->get('data');
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
        if (!$this->blueDot->repository()->isCurrentlyUsingRepository('presentation')) {
            $this->blueDot->useRepository('presentation');
        }

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
            'frontend_url' => sprintf('langland/lesson/%s/%d', $lessonName, $data['id'])
        ];

        $this->blueDot->useRepository('presentation');

        return $data;
    }
}