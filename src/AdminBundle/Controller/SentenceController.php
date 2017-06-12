<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Sentence;
use Library\Event\PreUpdateEvent;
use AdminBundle\Form\Type\SentenceType;
use Symfony\Component\HttpFoundation\Request;
use Library\Event\PrePersistEvent;

class SentenceController extends RepositoryController
{
    public function indexAction($courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        if (empty($course)) {
            throw $this->createNotFoundException();
        }

        $sentences = $this->getRepository('AdminBundle:Sentence')->findBy(array(
            'course' => $course,
        ), array(
            'id' => 'DESC',
        ));

        return $this->render('::Admin/Sentence/index.html.twig', array(
            'course' => $course,
            'sentences' => $sentences,
        ));
    }

    public function createAction(Request $request, $courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        if (empty($course)) {
            throw $this->createNotFoundException();
        }

        $sentence = new Sentence();
        $form = $this->createForm(SentenceType::class, $sentence, array(
            'validation_groups' => array('Default', 'Create')
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->dispatchEvent(PrePersistEvent::class, array(
                    'sentence' => $sentence,
                    'course' => $course,
                ));

                $em->persist($sentence);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Course created successfully')
                );

                return $this->redirectToRoute('admin_sentence_create', array(
                    'courseId' => $course->getId(),
                ));
            }
        }

        return $this->render('::Admin/Sentence/create.html.twig', array(
            'course' => $course,
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $courseId, $sentenceId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        if (empty($course)) {
            throw $this->createNotFoundException();
        }

        $sentence = $this->getRepository('AdminBundle:Sentence')->find($sentenceId);
        $form = $this->createForm(SentenceType::class, $sentence);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->dispatchEvent(PrePersistEvent::class, array(
                    'sentence' => $sentence,
                    'course' => $course,
                ));

                $this->dispatchEvent(PreUpdateEvent::class, array(
                    'sentence' => $sentence
                ));

                $em->persist($sentence);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Course edited successfully')
                );

                return $this->redirectToRoute('admin_sentence_edit', array(
                    'courseId' => $course->getId(),
                    'sentenceId' => $sentence->getId(),
                ));
            }
        }

        return $this->render('::Admin/Sentence/edit.html.twig', array(
            'course' => $course,
            'sentence' => $sentence,
            'form' => $form->createView(),
        ));
    }
}