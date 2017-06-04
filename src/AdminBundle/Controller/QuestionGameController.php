<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Game\QuestionGame;
use AdminBundle\Event\PreUpdateEvent;
use AdminBundle\Form\Type\QuestionGameType;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Event\PrePersistEvent;
use AdminBundle\Event\PostPersistEvent;

class QuestionGameController extends RepositoryController
{
    public function createAction(Request $request, $courseId)
    {
        $question = new QuestionGame();
        $form = $this->createForm(QuestionGameType::class, $question, array(
            'question' => $question,
            'validation_groups' => array('Default', 'Create'),
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $this->dispatchEvent(PrePersistEvent::class, array(
                'questionGame' => $question,
            ));

            $em->persist($question);
            $em->flush();

            $this->addFlash(
                'notice',
                sprintf('Question game created successfully')
            );

            return $this->redirectToRoute('admin_game_index_get', array(
                'courseId' => $courseId,
            ));
        }

        return $this->render('::Admin/QuestionGame/create.html.twig', array(
            'course' => $this->getRepository('AdminBundle:Course')->find($courseId),
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $courseId, $gameId)
    {
        $question = $this->getRepository('AdminBundle:Game\QuestionGame')->find($gameId);
        $form = $this->createForm(QuestionGameType::class, $question, array(
            'question' => $question,
            'validation_groups' => array('Default', 'Edit'),
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $this->dispatchEvent(PrePersistEvent::class, array(
                'questionGame' => $question,
            ));

            $this->dispatchEvent(PreUpdateEvent::class, array(
                'questionGame' => $question,
            ));


            $em->persist($question);
            $em->flush();

            $this->addFlash(
                'notice',
                sprintf('Question game edited successfully')
            );

            return $this->redirectToRoute('admin_question_game_edit', array(
                'courseId' => $courseId,
                'gameId' => $gameId,
            ));
        }

        return $this->render('::Admin/QuestionGame/edit.html.twig', array(
            'course' => $this->getRepository('AdminBundle:Course')->find($courseId),
            'form' => $form->createView(),
            'game' => $this->getRepository('AdminBundle:Game\QuestionGame')->find($gameId),
        ));
    }
}