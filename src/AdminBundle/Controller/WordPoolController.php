<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\ViewEntity\WordPool;
use AdminBundle\Form\Type\View\WordPoolType;
use Symfony\Component\HttpFoundation\Request;

class WordPoolController extends RepositoryController
{
    public function indexAction($courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        if (empty($course)) {
            throw $this->createNotFoundException();
        }

        $wordPool = $this->getRepository('AdminBundle:SentenceWordPool')->findAll();

        return $this->render('Admin/SentenceWordPool/index.html.twig', array(
            'course' => $course,
            'wordPool' => $wordPool,
        ));
    }

    public function createAction(Request $request, $courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        if (empty($course)) {
            throw $this->createNotFoundException();
        }

        $wordPool = new WordPool();
        $form = $this->createForm(WordPoolType::class, $wordPool);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                return $this->redirectToRoute('word_pool_index', array(
                    'course' => $course,
                ));
            }
        }

        return $this->render('::Admin/SentenceWordPool/create.html.twig', array(
            'course' => $course,
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $courseId, $wordPoolId)
    {

    }
}