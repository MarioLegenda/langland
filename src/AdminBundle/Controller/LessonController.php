<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Course;
use AdminBundle\Entity\Lesson;
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
                $lesson->setCourse($course);

                if ($lesson->getIsInitialLesson()) {
                    $this->uncheckInitialLessons($course);
                    $lesson->setIsInitialLesson(true);
                }

                $em->persist($lesson);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Lesson created successfully')
                );

                return $this->redirectToRoute('lesson_create', array(
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
                $lesson->setCourse($course);

                $this->removeDeletetedLessonTexts($lesson);

                if ($lesson->getIsInitialLesson() === true) {
                    $this->uncheckInitialLessons($course);
                    $lesson->setIsInitialLesson(true);
                }

                $em->persist($lesson);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Lesson edited successfully')
                );

                return $this->redirectToRoute('lesson_edit', array(
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

    public function uncheckInitialLessons(Course $course)
    {
        foreach ($course->getLessons() as $dbLesson) {
            $dbLesson->setIsInitialLesson(false);

            $this->get('doctrine')->getManager()->persist($dbLesson);
        }

        $this->get('doctrine')->getManager()->flush();

        return true;
    }

    private function removeDeletetedLessonTexts(Lesson $lesson)
    {
        $em = $this->get('doctrine')->getManager();

        $dbLessonTexts = $em->getRepository('AdminBundle:LessonText')->findBy(array(
            'lesson' => $lesson,
        ));

        foreach ($dbLessonTexts as $text) {
            if (!$lesson->hasLessonText($text)) {
                $em->remove($text);
            }
        }
    }
}
