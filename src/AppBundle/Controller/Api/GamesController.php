<?php

namespace AppBundle\Controller\Api;

use AppBundle\GameScenario\DecisionFactory;

class GamesController extends CommonOperationController
{
    public function findAvailableGamesAction($learningUserCourseId)
    {
        $learningUserCourse = $this->getRepository('AppBundle\Entity\LearningUserCourse')->find($learningUserCourseId);

        $games = $this->getRepository('AppBundle\Entity\LearningUserGame')
            ->findAvailableGamesByCourse($learningUserCourse);

        if (empty($games)) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse(
            $this->serialize($games, array('games_list'))
        );
    }

	public function decideGameTypeAction($learningUserGameId)
	{
		$learningUserGame = $this->getRepository('AdminBundle:learningUserGame')->find($learningUserGameId);

		$decisionFactory = new DecisionFactory($learningUserGame);

		$decision = $decisionFactory->makeDecision();

		
	}

    public function initializeSelectedGameAction($learningUserGameId)
    {
        $learningUserGame = $this->getRepository('AppBundle:LearningUserGame')->find($learningUserGameId);

        if (empty($learningUserGame)) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse();
    }
}
