<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Game\QuestionGame;
use AdminBundle\Form\Type\QuestionGameType;
use Symfony\Component\HttpFoundation\Request;

class QuestionGameController extends RepositoryController
{
    public function createAction(Request $request, $courseId)
    {
        $question = new QuestionGame();
        $form = $this->createForm(QuestionGameType::class, $question, array(
            'question' => $question,
        ));

        $form->handleRequest($request);

        if ($form->isValid() and $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();

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

    public function editAction()
    {

    }
}