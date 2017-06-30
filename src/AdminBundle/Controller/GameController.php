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
        ));
    }
}