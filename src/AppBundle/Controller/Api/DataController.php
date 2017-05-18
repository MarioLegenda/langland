<?php

namespace AppBundle\Controller\Api;

class DataController extends CommonOperationController
{
    public function lessonListAction($learningUserCourseId)
    {
        $learningUserCourse = $this->getRepository('AppBundle:LearningUserCourse')->find($learningUserCourseId);

        $lessons = $learningUserCourse->getLearningUserLessons();

        $serialized = $this->serialize($lessons, array('lesson_list'));

        return $this->createSuccessJsonResponse($serialized);
    }
}