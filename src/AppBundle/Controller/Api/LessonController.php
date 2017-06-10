<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\LearningUserLesson;
use AppBundle\Entity\Progress;
use Symfony\Component\HttpFoundation\Request;

class LessonController extends CommonOperationController
{
    public function markLessonPassedAction(Request $request)
    {
        $learningUserLessonId = $request->request->get('learningUserLessonId');

        $learningUserLesson = $this->getRepository('AppBundle:LearningUserLesson')->find($learningUserLessonId);

        if ($learningUserLesson instanceof LearningUserLesson) {
            if ($learningUserLesson->getHasPassed() === false) {
                $learningUserLesson->setHasPassed(true);
                $learningUserLesson->setIsEligable(false);

                $courseName = $request->request->get('courseName');
                $learningUserCourseId = $request->request->get('learningUserCourseId');

                $progress = new Progress();

                $progress->setText(
                    sprintf('You have unlocked games for lesson %s. Go to :0 and pass these games to unlock next lesson', $learningUserLesson->getLesson()->getName())
                );

                $base = ($this->get('kernel')->getEnvironment() === 'dev') ? '/app_dev.php/' : '/';
                $url = sprintf('%s/langland/dashboard/%s/%s/games', $base,$courseName, $learningUserCourseId);

                $progress->setUrls(json_encode(array($url)));

                $this->save(array($learningUserLesson, $progress));
            }

            return $this->createSuccessJsonResponse();
        }

        return $this->createFailedJsonResponse();
    }

    public function markLessonEligableAction(Request $request)
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