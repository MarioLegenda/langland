<?php

namespace AppBundle\Controller\Api;

class MethodAppDataController extends CommonOperationController
{
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