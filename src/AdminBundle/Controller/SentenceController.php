<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Sentence;
use AdminBundle\Form\Type\SentenceType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

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
        $form = $this->createForm(SentenceType::class, $sentence);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->removeEmptyTranslations($sentence);

                $existingSentence = $this->getRepository('AdminBundle:Sentence')->findBy(array(
                    'name' => $sentence->getName(),
                ));

                if (!empty($existingSentence)) {
                    $form->addError(new FormError(
                        sprintf('Sentence with internal name \'%s\' already exists', $sentence->getName())
                    ));

                    return $this->render('AdminBundle:Sentence:create.html.twig', array(
                        'course' => $course,
                    ));
                }

                $sentence->setCourse($course);

                $em->persist($sentence);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Course created successfully')
                );

                return $this->redirectToRoute('sentence_create', array(
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

                $this->removeEmptyTranslations($sentence);

                $em->persist($sentence);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Course edited successfully')
                );

                return $this->redirectToRoute('sentence_edit', array(
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

    private function removeEmptyTranslations(Sentence $sentence)
    {
        foreach ($sentence->getSentenceTranslations() as $translation) {
            if (is_null($translation->getName())) {
                $sentence->removeSentenceTranslation($translation);
            }
        }
    }
}