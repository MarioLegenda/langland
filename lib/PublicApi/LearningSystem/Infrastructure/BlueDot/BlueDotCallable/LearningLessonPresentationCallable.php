<?php

namespace PublicApi\LearningSystem\Infrastructure\BlueDot\BlueDotCallable;

use BlueDot\Configuration\Flow\Service\BaseService;

class LearningLessonPresentationCallable extends BaseService
{
    /**
     * @inheritdoc
     */
    public function run(): array
    {
        if ($this->blueDot->repository()->isCurrentlyUsingRepository('presentation')) {
            $this->blueDot->useRepository('presentation');
        }

        $learningUserId = $this->parameters['learning_user_id'];
        $languageId = $this->parameters['language_id'];

        $promise = $this->blueDot->execute('scenario.get_lessons_presentation_by_learning_user', [
            'find_learning_lessons' => [
                'learning_user_id' => $learningUserId,
            ],
            'find_courses' => [
                'language_id' => $languageId,
            ],
        ]);

        $learningLessons = $promise->getResult()->get('find_learning_lessons')['data'];
        $courses = $promise->getResult()->get('find_courses')['data'];

        $presentation = [
            'blocks' => [
                'courses' => $this->bindLessonsToCourses($learningLessons, $courses),
            ],
            'courses' => $courses,
            'learning_lessons' => $learningLessons,
        ];

        return $presentation;
    }
    /**
     * @param array $learningLessons
     * @param array $courses
     * @return array
     */
    private function bindLessonsToCourses(array $learningLessons, array $courses): array
    {
        $finalPresentation = [];
        foreach ($courses as $course) {
            $courseId = (int) $course['id'];

            $lessons = $this->findLessonsByCourseId($courseId, $learningLessons);

            $finalPresentation[] = [
                'course' => $course,
                'items' => $lessons
            ];
        }

        return $finalPresentation;
    }
    /**
     * @param int $courseId
     * @param array $learningLessons
     * @return array
     */
    private function findLessonsByCourseId(int $courseId, array $learningLessons): array
    {
        $lessons = [];
        foreach ($learningLessons as $lesson) {
            if ($courseId === $lesson['course_id']) {
                $lessons[] = $lesson;
            }
        }

        return $lessons;
    }
}