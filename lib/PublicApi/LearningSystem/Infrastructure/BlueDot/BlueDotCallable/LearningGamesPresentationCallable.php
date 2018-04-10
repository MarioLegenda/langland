<?php

namespace PublicApi\LearningSystem\Infrastructure\BlueDot\BlueDotCallable;

use BlueDot\Configuration\Flow\Service\BaseService;

class LearningGamesPresentationCallable extends BaseService
{
    /**
     * @inheritdoc
     */
    public function run(): array
    {
        if (!$this->blueDot->repository()->isCurrentlyUsingRepository('presentation')) {
            $this->blueDot->useRepository('presentation');
        }

        $learningUserId = $this->parameters['learning_user_id'];

        $promise = $this->blueDot->execute('simple.select.get_games_presentation_by_learning_user', [
            'learning_user_id' => $learningUserId,
        ]);

        $games = $promise->getResult()->get('data');

        $presentation = [
            'blocks' => [
                'courses' => $this->resolveData($games),
            ],
        ];

        return $presentation;
    }
    /**
     * @param array $games
     * @return array
     */
    private function resolveData(array $games): array
    {
        $resolvedData = [];
        foreach ($games as $game) {
            $learningMetadataId = $game['learning_metadata_id'];

            $course = $this->getCourse($learningMetadataId);

            $this->putGameInCourse($course, $game, $resolvedData);
        }

        return $resolvedData;
    }
    /**
     * @param array $course
     * @param array $game
     * @param array $resolvedData
     */
    private function putGameInCourse(array $course, array $game, array &$resolvedData)
    {
        if (empty($resolvedData)) {
            $resolvedData[] = [
                'course' => $course,
                'items' => [$game],
            ];
        }
        foreach ($resolvedData as $item) {
            $itemCourse = $item['course'];

            if ($itemCourse['id'] === $course['id']) {
                $item['items'][] = $game;

                break;
            }
        }
    }
    /**
     * @param int $learningMetadataId
     * @return array
     */
    private function getCourse(int $learningMetadataId): array
    {
        $languageId = $this->parameters['language_id'];
        $learningUserId = $this->parameters['learning_user_id'];

        $promise = $this->blueDot->execute('simple.select.get_course_id_by_learning_lesson', [
            'learning_user_id' => $learningUserId,
            'learning_metadata_id' => $learningMetadataId,
        ]);

        $courseId = (int) $promise->getResult()->get('data')['course_id'];

        $promise = $this->blueDot->execute('simple.select.find_course_by_id', [
            'language_id' => $languageId,
            'course_id' => $courseId,
        ]);

        return $promise->getResult()->get('data');
    }
}