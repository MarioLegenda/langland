<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 20.05.17.
 * Time: 14:53
 */

namespace AdminBundle\Controller;

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

    public function createAction(Request $request, $courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        return $this->render('::Admin/Game/create.html.twig', array(
            'course' => $course,
        ));
    }
}