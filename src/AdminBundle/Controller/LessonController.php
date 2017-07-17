<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Lesson;
use Library\Event\PrePersistEvent;
use Library\Event\PreUpdateEvent;
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

        if ($form->isSubmitted() and $form->isValid()) {
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
        } else if ($form->isSubmitted() and !$form->isValid()) {
            $response = $this->render('::Admin/Lesson/create.html.twig', array(
                'course' => $course,
                'form' => $form->createView(),
            ));

            $response->setStatusCode(400);

            return $response;
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

        if ($form->isSubmitted() and $form->isValid()) {
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
        } else if ($form->isSubmitted() and $form->isValid()) {
            $response = $this->render('::Admin/Lesson/edit.html.twig', array(
                'course' => $course,
                'form' => $form->createView(),
            ));

            $response->setStatusCode(400);

            return $response;
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

    public function findLessonsByCourseAction(Request $request, $courseId)
    {
        $responseCreator = $this->get('app_response_creator');

        if ($request->getMethod() !== 'GET') {
            return $responseCreator->createMethodNotAllowedResponse();
        }

        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        if (empty($course)) {
            return $responseCreator->createNoResourceResponse();
        }

        $lessons = $this->getRepository('AdminBundle:Lesson')->findBy(array(
            'course' => $course,
        ));

        if (empty($lessons)) {
            return $responseCreator->createNoResourceResponse();
        }

        if ($request->query->has('type')) {
            if ($request->query->get('type') === 'autocomplete') {
                $autocomplete = array();
                foreach ($lessons as $lesson) {
                    $temp = array();

                    $temp['label'] = $lesson->getName();
                    $temp['value'] = $lesson->getId();

                    $autocomplete[] = $temp;
                }

                return $responseCreator->createResourceAvailableResponse($autocomplete);
            } else if ($request->query->get('type') === 'withText') {
                return $responseCreator->createSerializedResponse($lessons, array('lesson_list', 'lesson_text'));
            }
        } else {
            return $responseCreator->createSerializedResponse($lessons, array('lesson_list'));
        }

        return $responseCreator->createBadRequestResponse();
    }
}
