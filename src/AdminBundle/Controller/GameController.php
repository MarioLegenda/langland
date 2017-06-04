<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Course;
use Symfony\Component\HttpFoundation\Request;

class GameController extends RepositoryController
{
    public function indexAction(Request $request, $courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        return $this->render('::Admin/Game/index.html.twig', array(
            'course' => $course,
            'games' => $this->getGameList($request, $course),
        ));
    }

    public function createWordGameAction($courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        return $this->render('::Admin/Game/create.html.twig', array(
            'course' => $course,
        ));
    }

    public function removeAction($courseId, $gameId)
    {
        $game = $this->getRepository('AdminBundle:Game\WordGame')
            ->findSingleGameByCourse(
                $this->getRepository('AdminBundle:Course')->find($courseId),
                $gameId
            );

        if (is_null($game)) {
            return $this->redirectToRoute('admin_game_index_get', array(
                'courseId' => $courseId
            ));
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($game);
        $em->flush();

        return $this->redirectToRoute('admin_game_index_get', array(
            'courseId' => $courseId
        ));
    }

    private function getGameList(Request $request, Course $course)
    {
        if (!$request->query->has('gameType')) {
            return $this->getRepository('AdminBundle:Game\WordGame')->findGamesByCourse($course);
        }
    }
}