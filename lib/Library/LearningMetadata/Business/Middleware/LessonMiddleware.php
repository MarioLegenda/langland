<?php

namespace Library\LearningMetadata\Business\Middleware;

use Library\Infrastructure\Helper\Deserializer;
use Library\LearningMetadata\Business\Implementation\CourseImplementation;
use Library\LearningMetadata\Business\ViewModel\Lesson\LessonView;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Entity\Course;

class LessonMiddleware
{
    /**
     * @param array $data
     * @param CourseImplementation $courseImplementation
     * @param Deserializer $deserializer
     * @return array
     */
    public function createNewLessonMiddleware(
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

        $lessonView->setUuid(Uuid::uuid4());

        return [
            'course' => $course,
            'lessonView' => $lessonView,
        ];
    }
}