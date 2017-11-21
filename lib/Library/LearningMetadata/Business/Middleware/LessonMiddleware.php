<?php

namespace Library\LearningMetadata\Business\Middleware;

use AdminBundle\Entity\Lesson;
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
     * @param Deserializer $deserializer
     * @return array
     */
    public function createNewLessonMiddleware
    (
        array $data,
        CourseImplementation $courseImplementation,
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

        return [
            'course' => $course,
            'lessonView' => $lessonView,
        ];
    }

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

        return [
            'lesson' => $lesson,
            'lessonView' => $lessonView,
        ];
    }
}