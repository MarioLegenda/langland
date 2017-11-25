<?php

namespace Library\LearningMetadata\Business\Middleware;

use AdminBundle\Entity\Lesson;
use Library\Exception\HttpResponseStatus;
use Library\Exception\RequestStatusException;
use Library\Infrastructure\Helper\Deserializer;
use Library\LearningMetadata\Business\Implementation\CourseImplementation;
use Library\LearningMetadata\Business\Implementation\CourseManagment\LessonImplementation;
use Library\LearningMetadata\Business\ViewModel\Lesson\LessonView;
use AdminBundle\Entity\Course;

class LessonMiddleware
{
    /**
     * @param array $data
     * @param CourseImplementation $courseImplementation
     * @param LessonImplementation $lessonImplementation
     * @param Deserializer $deserializer
     * @throws \RuntimeException
     * @throws RequestStatusException
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

        /** @var LessonView $lessonView */
        $lessonView = $deserializer->create(
            $data,
            LessonView::class
        );

        $lesson = $lessonImplementation->tryFindByName($lessonView->getName());

        if ($lesson instanceof Lesson) {
            throw new RequestStatusException(new HttpResponseStatus(
                400,
                [sprintf('Lesson with name \'%s\' already exists', $lessonView->getName())]
            ));
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
     * @throws RequestStatusException
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
        $lessonView = $deserializer->create(
            $data,
            LessonView::class
        );

        $existing = $lessonImplementation->tryFindByName($lessonView->getName());

        if ($existing instanceof Lesson) {
            if ($lesson->getName() !== $existing->getName()) {
                throw new RequestStatusException(new HttpResponseStatus(
                    400,
                    [sprintf('Lesson with name \'%s\' already exists', $lessonView->getName())]
                ));
            }
        }

        $lesson->setName($lessonView->getName());

        return [
            'lesson' => $lesson,
            'lessonView' => $lessonView,
        ];
    }
}