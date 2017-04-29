<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\SentenceWordPool;
use AdminBundle\Entity\ViewEntity\WordPool as ViewWordPool;
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

        $viewWordPool = new ViewWordPool();
        $form = $this->createForm(WordPoolType::class, $viewWordPool);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();
                $ids = $viewWordPool->resolveIds();

                $words = $this->getRepository('AdminBundle:Word')->findMultipleById($ids);
                $wordPool = new SentenceWordPool();
                $wordPool->setName($viewWordPool->getName());

                foreach ($words as $word) {
                    $wordPool->addWord($word);
                }

                $wordPool->setCourse($course);

                $em->persist($wordPool);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Word pool created successfully')
                );

                return $this->redirectToRoute('word_pool_create', array(
                    'courseId' => $course->getId(),
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
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        if (empty($course)) {
            throw $this->createNotFoundException();
        }

        $wordPool = $this->getRepository('AdminBundle:SentenceWordPool')->find($wordPoolId);

        $ids = array();
        foreach ($wordPool->getWords() as $word) {
            $ids[] = $word->getId();
        }

        $viewWordPool = new ViewWordPool();
        $viewWordPool->setId($wordPool->getId());
        $viewWordPool->setWordIds(implode(',', $ids));
        $viewWordPool->setName($wordPool->getName());

        $form = $this->createForm(WordPoolType::class, $viewWordPool);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();
                $inputIds = $viewWordPool->resolveIds();

                $words = $this->getRepository('AdminBundle:Word')->findMultipleById($inputIds);

                foreach ($words as $word) {
                    $wordPool->addWord($word);
                }

                $toBeRemovedIds = $this->compareIds($ids, $inputIds);

                if (!empty($toBeRemovedIds)) {
                    $words = $this->getRepository('AdminBundle:Word')->findMultipleById($toBeRemovedIds);

                    foreach ($words as $word) {
                        $wordPool->getWords()->removeElement($word);
                    }
                }

                $em->persist($wordPool);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Word pool edited successfully')
                );

                return $this->redirectToRoute('word_pool_edit', array(
                    'courseId' => $course->getId(),
                    'wordPoolId' => $wordPool->getId(),
                ));
            }
        }

        return $this->render('::Admin/SentenceWordPool/edit.html.twig', array(
            'course' => $course,
            'form' => $form->createView(),
            'wordPool' => $viewWordPool,
        ));
    }

    public function removeAction($courseId, $wordPoolId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        if (empty($course)) {
            throw $this->createNotFoundException();
        }

        $wordPool = $this->getRepository('AdminBundle:SentenceWordPool')->find($wordPoolId);

        $em = $this->get('doctrine')->getManager();

        $em->remove($wordPool);
        $em->flush();

        return $this->redirectToRoute('word_pool_index', array(
            'courseId' => $course->getId(),
        ));
    }

    private function compareIds(array $dbIds, array $inputIds)
    {
        return array_diff($dbIds, $inputIds);
    }
}