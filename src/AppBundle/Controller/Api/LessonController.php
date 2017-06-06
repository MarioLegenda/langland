<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\LearningUserCourse;

class LessonController extends CommonOperationController
{
    public function lessonListAction($courseName, $learningUserCourseId)
    {
        $learningUserCourse = $this->getRepository('AppBundle:LearningUserCourse')->find($learningUserCourseId);

        if ($learningUserCourse instanceof LearningUserCourse) {
            return $this->render('::App/Dashboard/dashboard.html.twig');
        }

        throw $this->createNotFoundException();
    }
}