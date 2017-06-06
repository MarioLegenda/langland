<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Course;
use AdminBundle\Form\Type\CourseType;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Event\PrePersistEvent;

class CourseController extends RepositoryController
{
    public function indexAction()
    {
        $courses = $this->getRepository('AdminBundle:Course')->findBy(array(), array(
            'id' => 'DESC',
        ));

        return $this->render('::Admin/Course/CRUD/index.html.twig', array(
            'courses' => $courses,
        ));
    }

    public function createAction(Request $request)
    {
        $language = $this->getRepository('AdminBundle:Language')->find(1);

        if (empty($language)) {
            return $this->render('::Admin/Course/CRUD/create.html.twig', array(
                'no_language' => true,
            ));
        }

        $course = new Course();
        $form = $this->createForm(CourseType::class, $course, array(
            'course' => $course,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->dispatchEvent(PrePersistEvent::class, array(
                    'course' => $course,
                ));

                $em->persist($course);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Course created successfully')
                );

                return $this->redirectToRoute('admin_course_create');
            }
        }

        return $this->render('::Admin/Course/CRUD/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $id)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($id);

        if (empty($course)) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CourseType::class, $course, array(
            'course' => $course,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->dispatchEvent(PrePersistEvent::class, array(
                    'course' => $course,
                ));

                $em->persist($course);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Course edited successfully')
                );

                return $this->redirectToRoute('admin_course_edit', array(
                    'id' => $course->getId(),
                ));
            }
        }

        return $this->render('::Admin/Course/CRUD/edit.html.twig', array(
            'form' => $form->createView(),
            'course' => $course,
        ));
    }

    public function manageAction($courseId)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($courseId);

        if (empty($course)) {
            throw $this->createNotFoundException();
        }

        return $this->render('::Admin/Course/dashboard.html.twig', array(
            'course' => $course,
        ));
    }

    private function unmarkInitialCourse()
    {
        $initialCourses = $this->getRepository('AdminBundle:Course')->findBy(array(
            'initialCourse' => true,
        ));

        foreach ($initialCourses as $course) {
            $course->setInitialCourse(false);

            $this->getDoctrine()->getManager()->persist($course);
        }
    }
}
