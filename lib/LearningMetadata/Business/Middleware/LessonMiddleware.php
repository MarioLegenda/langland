<?php

namespace LearningMetadata\Business\Middleware;

use AdminBundle\Entity\Lesson;
use Library\Exception\RequestStatusException;
use Library\Infrastructure\Helper\Deserializer;
use LearningMetadata\Business\Implementation\CourseImplementation;
use LearningMetadata\Business\Implementation\CourseManagment\LessonImplementation;
use LearningMetadata\Business\ViewModel\Lesson\LessonView;
use AdminBundle\Entity\Course;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LessonMiddleware
{
    /**
     * @param array $data
     * @param CourseImplementation $courseImplementation
     * @param LessonImplementation $lessonImplementation
     * @param Deserializer $deserializer
     * @throws \RuntimeException
     * @return array
     */
    public function createNewLessonMiddleware
    (
        array $data,
        CourseImplementation $courseImplementation,
        LessonImplementation $lessonImplementation,
        Deserializer $deserializer
    ): array {
        $courseId = $data['course'];

        $course = $courseImplementation->findCourse($courseId);

        if (!$course instanceof Course) {
            throw new \RuntimeException(sprintf('Course not found'));
        }

        $deserializer->create(
            $data,
            LessonView::class
        );

        if ($deserializer->hasErrors()) {
            throw new \RuntimeException($deserializer->getErrorsString());
        }

        /** @var LessonView $lessonView */
        $lessonView = $deserializer->getSerializedObject();

        $lesson = $lessonImplementation->tryFindByName($lessonView->getName());

        if ($lesson instanceof Lesson) {
            throw new BadRequestHttpException(sprintf('Lesson with name \'%s\' already exists', $lessonView->getName()));
        }

        return [
            'course' => $course,
            'lessonView' => $lessonView,
        ];
    }
    /**
     * @param array $data
     * @param LessonImplementation $lessonImplementation
     * @param Deserializer $deserializer
     * @throws \RuntimeException
     * @return array
     */
    public function createExistingLessonMiddleware
    (
        array $data,
        LessonImplementation $lessonImplementation,
        Deserializer $deserializer
    ): array {
        $lessonId = $data['id'];

        $lesson = $lessonImplementation->find($lessonId);

        if (!$lesson instanceof Lesson) {
            throw new \RuntimeException(sprintf('Lesson not found'));
        }

        /** @var LessonView $lessonView */
        $deserializer->create(
            $data,
            LessonView::class
        );

        if ($deserializer->hasErrors()) {
            throw new \RuntimeException($deserializer->getErrorsString());
        }

        /** @var LessonView $lessonView */
        $lessonView = $deserializer->getSerializedObject();

        $existing = $lessonImplementation->tryFindByName($lessonView->getName());

        if ($existing instanceof Lesson) {
            if ($lesson->getName() !== $existing->getName()) {
                throw new BadRequestHttpException(sprintf('Lesson with name \'%s\' already exists', $lessonView->getName()));
            }
        }

        $lesson->setName($lessonView->getName());
        $lesson->setLearningOrder($lessonView->getLearningOrder());

        return [
            'lesson' => $lesson,
            'lessonView' => $lessonView,
        ];
    }
}