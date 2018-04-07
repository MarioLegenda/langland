<?php

namespace PublicApi\LearningSystem\Infrastructure\BlueDot\BlueDotCallable;

use BlueDot\Configuration\Flow\Service\BaseService;
use BlueDot\Entity\Entity;
use BlueDot\Entity\PromiseInterface;

class CreateLearningMetadataService extends BaseService
{
    /**
     * @inheritdoc
     */
    public function run(): array
    {
        $lessonIds = $this->getLessonIds();

        $createLearningMetadataPromises = $this->createLearningMetadata($lessonIds);

        $this->updateLearningLessons($createLearningMetadataPromises);

        /** @var Entity $result */
        $result = $createLearningMetadataPromises[0]->getResult();
        $learningMetadataId = (int) $result->get('create_learning_metadata')['last_insert_id'];

        return [
            'learningMetadataId' => $learningMetadataId,
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

        foreach ($lessonIds as $lessonId) {
            $this->blueDot->prepareExecution(
                'scenario.create_learning_metadata', [
                'create_learning_lesson' => [
                    'lesson_id' => $lessonId,
                ],
                'create_learning_metadata' => [
                    'learning_user_id' => $learningUserId,
                ],
            ]);
        }

        /** @var PromiseInterface[] $createLearningMetadataPromises */
        return $this->blueDot->executePrepared();
    }
    /**
     * @param PromiseInterface[] $createLearningMetadataPromises
     */
    private function updateLearningLessons(array $createLearningMetadataPromises)
    {
        /** @var PromiseInterface $promise */
        foreach ($createLearningMetadataPromises as $promise) {
            if ($promise->isSuccess()) {
                $learningMetadataId = $promise->getResult()->get('create_learning_metadata')['last_insert_id'];
                $learningLessonId = $promise->getResult()->get('create_learning_lesson')['last_insert_id'];

                $this->blueDot->execute('simple.update.update_learning_lesson', [
                    'learning_metadata_id' => $learningMetadataId,
                    'learning_lesson_id' => $learningLessonId,
                ]);
            }
        }

        $this->blueDot->executePrepared();
    }
}