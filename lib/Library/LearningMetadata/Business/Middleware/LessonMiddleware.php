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
     * @param Request $request
     * @param CourseImplementation $courseImplementation
     * @param Deserializer $deserializer
     * @return array
     */
    public function createNewLessonMiddleware(
        Request $request,
        CourseImplementation $courseImplementation,
        Deserializer $deserializer
    ): array {
        $data = $request->request->all();
        $courseId = $data['course'];

        $course = $courseImplementation->findCourse($courseId);

        if (!$course instanceof Course) {
            throw new \RuntimeException(sprintf('Course not found'));
        }

        /** @var LessonView $lessonView */
        $lessonView = $deserializer->create(
            $request->request->all(),
            LessonView::class
        );

        $lessonView->setInternalName($this->createLessonViewInternalName($lessonView));

        return [
            'course' => $course,
            'lessonView' => $lessonView,
        ];
    }
    /**
     * @param LessonView $lessonView
     * @return string
     */
    private function createLessonViewInternalName(LessonView $lessonView): string
    {
        $hash = substr(Uuid::uuid4()->toString(), 5, 8);
        $internalName = $lessonView->getName().$hash;

        return $internalName;
    }
}