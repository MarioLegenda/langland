<?php

namespace AppBundle\Controller\Api;

use AdminBundle\Entity\Game\QuestionGame;
use AdminBundle\Entity\Game\WordGame;
use AppBundle\Entity\LearningUserGame;
use AppBundle\Entity\LearningUserLesson;
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

                $lesson = $learningUserLesson->getLesson();

                $games = $lesson->getGames();

                foreach ($games as $game) {
                    $learningUserGame = new LearningUserGame();

                    if ($game instanceof WordGame) {
                        $learningUserGame->setWordGame($game);
                    } else if ($game instanceof QuestionGame) {
                        $learningUserGame->setQuestionGame($game);
                    }

                    $learningUserGame->setLearningUserLesson($learningUserLesson);

                    $this->getManager()->persist($learningUserGame);
                }

                $this->getManager()->flush();

                $this->save($learningUserLesson);
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

        if (empty($lessons)) {
            return $this->createFailedJsonResponse();
        }

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