<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\LessonWorkingData;
use AdminBundle\Form\Type\LessonWorkingDataType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class LessonWorkingDataController extends RepositoryController
{
    public function createAction(Request $request, $lessonId)
    {
        $lesson = $this->getRepository('AdminBundle:Lesson')->find($lessonId);

        if ($lesson->getWorkingData() instanceof LessonWorkingData) {
            return $this->redirectToRoute('lesson_working_data_edit', array(
                'lessonId' => $lessonId,
            ));
        }

        $workingData = new LessonWorkingData();
        $form = $this->createForm(LessonWorkingDataType::class, $workingData, array(
            'lesson' => $lesson,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $sentences = $workingData->getSentences();
                $wordPools = $workingData->getWordPools();

                if (empty($sentences) or empty($wordPools)) {
                    $form->addError(new FormError('You have to add at least one sentence and word pool to this lessons working data'));

                    return $this->render('::Admin/Course/Lesson/CRUD/create.html.twig', array(
                        'form' => $form->createView(),
                        'lesson' => $lesson,
                    ));
                }

                $lesson->setWorkingData($workingData);

                $em->persist($lesson);
                $em->persist($workingData);

                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Working data created successfully')
                );

                return $this->redirectToRoute('lesson_working_data_edit', array(
                    'lessonId' => $lessonId,
                ));
            }
        }

        return $this->render('::Admin/Course/Lesson/CRUD/create.html.twig', array(
            'form' => $form->createView(),
            'lesson' => $lesson,
        ));
    }

    public function editAction(Request $request, $lessonId)
    {
        $lesson = $this->getRepository('AdminBundle:Lesson')->find($lessonId);

        $workingData = $lesson->getWorkingData();
        $form = $this->createForm(LessonWorkingDataType::class, $workingData, array(
            'lesson' => $lesson,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $sentences = $workingData->getSentences();
                $wordPools = $workingData->getWordPools();

                if (empty($sentences) or empty($wordPools)) {
                    $form->addError(new FormError('You have to add at least one sentence and word pool to this lessons working data'));

                    return $this->render('::Admin/Course/Lesson/CRUD/create.html.twig', array(
                        'form' => $form->createView(),
                        'lesson' => $lesson,
                    ));
                }

                $em->persist($workingData);

                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Working data edited successfully')
                );

                return $this->redirectToRoute('lesson_working_data_edit', array(
                    'lessonId' => $lessonId,
                ));
            }
        }

        return $this->render('::Admin/Course/Lesson/CRUD/edit.html.twig', array(
            'form' => $form->createView(),
            'lesson' => $lesson,
        ));
    }
}