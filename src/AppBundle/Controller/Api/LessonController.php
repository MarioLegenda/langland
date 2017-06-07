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
            $learningUserLesson->setHasPassed(true);

            $this->save($learningUserLesson);

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