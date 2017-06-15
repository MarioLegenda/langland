<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\GameType;
use AdminBundle\Form\Type\GameTypeType;
use Library\CommonController;
use Symfony\Component\HttpFoundation\Request;

class GameTypeController extends CommonController
{
    public function indexAction($courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        $gameTypes = $this->getRepository('AdminBundle:GameType')->findAll();

        return $this->render('::Admin/GameType/index.html.twig', array(
            'gameTypes' => $gameTypes,
            'course' => $course,
        ));
    }

    public function createAction(Request $request, $courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        $gameType = new GameType();
        $form = $this->createForm(GameTypeType::class, $gameType);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($gameType);
            $em->flush();

            $this->addFlash(
                'notice',
                sprintf('Game type created successfully')
            );

            return $this->redirectToRoute('admin_game_type_create', array(
                'courseId' => $course->getId(),
            ));
        }

        return $this->render('::Admin/GameType/create.html.twig', array(
            'course' => $course,
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $courseId, $gameTypeId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        $gameType = $this->getRepository('AdminBundle:GameType')->find($gameTypeId);

        if (empty($gameType)) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(GameTypeType::class, $gameType);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($gameType);
            $em->flush();

            $this->addFlash(
                'notice',
                sprintf('Game type edited successfully')
            );

            return $this->redirectToRoute('admin_game_type_edit', array(
                'courseId' => $course->getId(),
                'gameTypeId' => $gameType->getId(),
            ));
        }

        return $this->render('::Admin/GameType/edit.html.twig', array(
            'course' => $course,
            'gameType' => $gameType,
            'form' => $form->createView(),
        ));
    }
}