<?php

namespace AdminBundle\Controller;

class GameController extends RepositoryController
{
    public function indexAction($courseId)
    {
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