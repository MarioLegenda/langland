<?php

namespace PublicApi\LearningSystem\Infrastructure\BlueDot\BlueDotCallable;

use BlueDot\Configuration\Flow\Service\BaseService;
use BlueDot\Entity\PromiseInterface;

class CreateLearningMetadataService extends BaseService
{
    /**
     * @inheritdoc
     */
    public function run(): array
    {
        $lessonIds = $this->getLessonIds();

        sort($lessonIds);

        $createLearningMetadataPromises = $this->createLearningMetadata($lessonIds);

        $learningMetadataId = (int) $createLearningMetadataPromises[0]->getResult()->get('create_learning_metadata')['last_insert_id'];
        $learningLessonId = (int) $createLearningMetadataPromises[0]->getResult()->get('create_learning_lesson')['last_insert_id'];

        return [
            'learningMetadataId' => $learningMetadataId,
            'learningLessonId' => $learningLessonId,
        ];
    }
    /**
     * @return array
     */
    private function getLessonIds(): array
    {
        $languageId = $this->parameters['language_id'];

        return $this->blueDot
            ->execute('simple.select.get_lesson_ids', [
                'language_id' => $languageId,
            ])
            ->getResult()
            ->toArray()['data']['id'];
    }

    /**
     * @param PromiseInterface[] $lessonIds
     * @return array
     */
    private function createLearningMetadata(array $lessonIds): array
    {
        $learningUserId = $this->parameters['learning_user_id'];

        $isFirst = true;
        foreach ($lessonIds as $lessonId) {
            $isAvailable = 0;

            if ($isFirst === true) {
                $isAvailable = 1;

                $isFirst = false;
            }

            $this->blueDot->prepareExecution(
                'scenario.create_learning_metadata', [
                'create_learning_lesson' => [
                    'lesson_id' => $lessonId,
                    'is_available' => $isAvailable,
                ],
                'create_learning_metadata' => [
                    'learning_user_id' => $learningUserId,
                ],
            ]);
        }

        /** @var PromiseInterface[] $createLearningMetadataPromises */
        return $this->blueDot->executePrepared();
    }
}