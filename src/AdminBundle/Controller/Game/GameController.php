<?php

namespace AdminBundle\Controller\Game;

use AdminBundle\Controller\RepositoryController;
use Symfony\Component\HttpFoundation\Request;

class GameController extends RepositoryController
{
    public function newAction(Request $request, $courseId)
    {
        $responseCreator = $this->get('app_response_creator');
        if ($this->getRepository('AdminBundle:Course')->find($courseId)) {
            return $responseCreator->createResourceNotFoundResponse();
        }


    }

    public function indexAction(Request $request, $courseId)
    {
        if ($request->getMethod() !== 'GET') {
            throw $this->createAccessDeniedException();
        }

        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        return $this->render('::Admin/Game/index.html.twig', array(
            'course' => $course,
        ));
    }

    public function createAction($courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        return $this->render('::Admin/Game/create.html.twig', array(
            'course' => $course,
        ));
    }
}