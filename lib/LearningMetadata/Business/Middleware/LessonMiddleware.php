<?php

namespace LearningMetadata\Business\Middleware;

use AdminBundle\Entity\Lesson;
use LearningMetadata\Business\Implementation\LessonImplementation;
use LearningMetadata\Business\ViewModel\Lesson\LessonView;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LessonMiddleware
{
    /**
     * @var LessonImplementation $lessonImplementation
     */
    private $lessonImplementation;
    /**
     * LessonMiddleware constructor.
     * @param LessonImplementation $lessonImplementation
     */
    public function __construct(
        LessonImplementation $lessonImplementation
    ) {
        $this->lessonImplementation = $lessonImplementation;
    }
    /**
     * @param LessonView $lessonView
     * @return array
     */
    public function createNewLessonMiddleware (LessonView $lessonView): array
    {
        $lesson = $this->lessonImplementation->tryFindByName($lessonView->getName());

        if ($lesson instanceof Lesson) {
            throw new BadRequestHttpException(sprintf('Lesson with name \'%s\' already exists', $lessonView->getName()));
        }

        return [
            'lessonView' => $lessonView,
        ];
    }
    /**
     * @param array $data
     * @throws \RuntimeException
     * @return array
     */
    public function createExistingLessonMiddleware(array $data): array
    {
        $lessonId = $data['id'];

        $lesson = $this->lessonImplementation->find($lessonId);

        if (!$lesson instanceof Lesson) {
            throw new \RuntimeException(sprintf('Lesson not found'));
        }

        /** @var LessonView $lessonView */
        $lessonView = $this->deserializer->create(
            $data,
            LessonView::class
        );

        $this->modelValidator->tryValidate($lessonView);

        if ($this->modelValidator->hasErrors()) {
            throw new \RuntimeException($this->modelValidator->getErrorsString());
        }

        $existing = $this->lessonImplementation->tryFindByName($lessonView->getName());

        if ($existing instanceof Lesson) {
            if ($lesson->getName() !== $existing->getName()) {
                throw new BadRequestHttpException(sprintf('Lesson with name \'%s\' already exists', $lessonView->getName()));
            }
        }

        $lesson->setName($lessonView->getName());
        $lesson->setDescription($lessonView->getDescription());
        $lesson->setLearningOrder($lessonView->getLearningOrder());

        return [
            'lesson' => $lesson,
            'lessonView' => $lessonView,
        ];
    }
}