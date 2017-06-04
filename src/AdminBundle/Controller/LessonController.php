<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Lesson;
use AdminBundle\Event\MultipleEntityEvent;
use AdminBundle\Event\PrePersistEvent;
use AdminBundle\Event\PreUpdateEvent;
use AdminBundle\Form\Type\LessonType;
use Symfony\Component\HttpFoundation\Request;

class LessonController extends RepositoryController
{
    public function indexAction($courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        $lessons = $this->getRepository('AdminBundle:Lesson')->findBy(array(
            'course' => $course,
        ));

        return $this->render('::Admin/Lesson/index.html.twig', array(
            'lessons' => $lessons,
            'course' => $course,
        ));
    }

    public function createAction(Request $request, $courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        if (empty($course)) {
            throw $this->createNotFoundException();
        }

        $lesson = new Lesson();
        $form = $this->createForm(LessonType::class, $lesson);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->dispatchEvent(PrePersistEvent::class, array(
                    'lesson' => $lesson,
                    'course' => $course,
                ));

                $em->persist($lesson);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Lesson created successfully')
                );

                return $this->redirectToRoute('admin_lesson_create', array(
                    'courseId' => $course->getId(),
                ));
            }
        }

        return $this->render('::Admin/Lesson/create.html.twig', array(
            'course' => $course,
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $courseId, $lessonId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        if (empty($course)) {
            throw $this->createNotFoundException();
        }

        $lesson = $this->getRepository('AdminBundle:Lesson')->find($lessonId);

        if (empty($lesson)) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(LessonType::class, $lesson);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->dispatchEvent(PrePersistEvent::class, array(
                    'lesson' => $lesson,
                    'course' => $course,
                ));

                $this->dispatchEvent(PreUpdateEvent::class, array(
                    'lesson' => $lesson,
                    'course' => $course,
                ));

                $em->persist($lesson);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Lesson edited successfully')
                );

                return $this->redirectToRoute('admin_lesson_edit', array(
                    'courseId' => $course->getId(),
                    'lessonId' => $lesson->getId(),
                ));
            }
        }

        return $this->render('::Admin/Lesson/edit.html.twig', array(
            'lesson' => $lesson,
            'course' => $course,
            'form' => $form->createView(),
        ));
    }

    public function manageAction($lessonId)
    {
        $lesson = $this->getRepository('AdminBundle:Lesson')->find($lessonId);

        if (empty($lesson)) {
            throw $this->createNotFoundException();
        }

        return $this->render('::Admin/Course/Lesson/dashboard.html.twig', array(
            'lesson' => $lesson,
        ));
    }
}
