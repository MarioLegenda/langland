<?php

namespace AdminBundle\Controller\Ajax;

use Library\ResponseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WordGameController extends ResponseController
{
    public function findGameWordsAction($courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);
        $language = $course->getLanguage();

        $words = $this->getRepository('AdminBundle:Word')->findBy(array(
            'language' => $language,
        ));

        if (empty($words)) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse($this->serialize($words, array('words_by_language')));
    }

    public function findLessonsByCourseAction($courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        $lessons = $course->getLessons();

        if (empty($lessons)) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse($this->serialize($lessons, array('lesson_list')));
    }

    public function findGamesAction($courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        return $this->createSuccessJsonResponse($this->serialize($course->getLessons(), array('word_games_list')));
    }

    public function createGameAction(Request $request)
    {
        $game = $this->get('admin.game_entity_creator')->createFromRequest($request);

        $response = $this->get('lib.manual_validator')->validate($game);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        $game->setUrl(\URLify::filter($game->getName()));

        $this->getManager()->persist($game);
        $this->getManager()->flush($game);

        return $this->createSuccessJsonResponse();
    }

    public function loadGameAction($courseId, $gameId)
    {
        $game = $this->getRepository('AdminBundle:Game\WordGame')->find($gameId);

        return $this->createSuccessJsonResponse($this->serialize($game, array('game')));
    }
}