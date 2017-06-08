<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\LearningUserLesson;
use Symfony\Component\HttpFoundation\Request;

class LessonController extends CommonOperationController
{
    public function markLessonPassedAction(Request $request)
    {
        $learningUserLessonId = $request->request->get('learningUserLessonId');

        $learningUserLesson = $this->getRepository('AppBundle:LearningUserLesson')->find($learningUserLessonId);

        if ($learningUserLesson instanceof LearningUserLesson) {
            $nextLearningUserLesson = $this->getRepository('AppBundle:LearningUserLesson')->find($learningUserLessonId + 1);

            if ($nextLearningUserLesson instanceof LearningUserLesson) {
                if ($nextLearningUserLesson->getIsEligable() === false) {
                    $nextLearningUserLesson->setIsEligable(true);
                    $this->save($nextLearningUserLesson);
                }
            }

            if ($learningUserLesson->getHasPassed() === false) {
                $learningUserLesson->setHasPassed(true);
                $learningUserLesson->setIsEligable(false);
                $this->save($learningUserLesson);
            }

            return $this->createSuccessJsonResponse();
        }

        return $this->createFailedJsonResponse();
    }

    public function lessonListAction($learningUserCourseId)
    {
        $learningUserCourse = $this->getRepository('AppBundle:LearningUserCourse')->find($learningUserCourseId);

        $lessons = $learningUserCourse->getLearningUserLessons();

        $serialized = $this->serialize($lessons, array('lesson_list'));

        return $this->createSuccessJsonResponse($serialized);
    }

    public function findLearningUserLessonAction($learningUserLessonId)
    {
        $learningUserLesson = $this->getRepository('AppBundle:LearningUserLesson')->find($learningUserLessonId);

        $serialized = $this->serialize($learningUserLesson, array('lesson_list', 'lesson_text'));

        return $this->createSuccessJsonResponse($serialized);
    }
}