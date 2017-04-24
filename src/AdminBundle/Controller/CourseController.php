<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Course;
use AdminBundle\Form\Type\CourseType;
use Symfony\Component\HttpFoundation\Request;

class CourseController extends RepositoryController
{
    public function indexAction()
    {
        $courses = $this->getRepository('AdminBundle:Course')->findAll();

        return $this->render('::Admin/Course/index.html.twig', array(
            'courses' => $courses,
        ));
    }

    public function createAction(Request $request)
    {
        $language = $this->getRepository('AdminBundle:Language')->find(1);

        if (empty($language)) {
            return $this->render('::Admin/Word/create.html.twig', array(
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
                $em = $this->get('doctrine')->getEntityManager();

                $em->persist($course);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Course created successfully')
                );

                return $this->redirectToRoute('course_create');
            }
        }

        return $this->render('::Admin/Course/create.html.twig', array(
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
                $em = $this->get('doctrine')->getEntityManager();

                $em->persist($course);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Course edited successfully')
                );

                return $this->redirectToRoute('course_edit', array(
                    'id' => $course->getId(),
                ));
            }
        }

        return $this->render('::Admin/Course/edit.html.twig', array(
            'form' => $form->createView(),
            'course' => $course,
        ));
    }
}
