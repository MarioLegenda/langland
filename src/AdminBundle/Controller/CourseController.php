<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Course;
use AdminBundle\Form\Type\CourseType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class CourseController extends RepositoryController
{
    public function indexAction()
    {
        $courses = $this->getRepository('AdminBundle:Course')->findAll();

        return $this->render('::Admin/Course/CRUD/index.html.twig', array(
            'courses' => $courses,
        ));
    }

    public function createAction(Request $request)
    {
        $language = $this->getRepository('AdminBundle:Language')->find(1);

        if (empty($language)) {
            return $this->render('::Admin/Word/CRUD/create.html.twig', array(
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

                $potencionalForm = $this->checkExistingCourse($course, $form);

                if ($potencionalForm instanceof FormInterface) {
                    return $this->render('::Admin/Course/CRUD/create.html.twig', array(
                        'form' => $form->createView(),
                    ));
                }

                $em->persist($course);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Course created successfully')
                );

                return $this->redirectToRoute('course_create');
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

                $potencionalForm = $this->checkExistingCourse($course, $form);

                if ($potencionalForm instanceof FormInterface) {
                    return $this->render('::Admin/Course/CRUD/create.html.twig', array(
                        'form' => $form->createView(),
                    ));
                }

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

        return $this->render('::Admin/Course/CRUD/edit.html.twig', array(
            'form' => $form->createView(),
            'course' => $course,
        ));
    }

    public function manageAction(Request $request, $id)
    {
        $course = $this->getRepository('AdminBundle:Course')->find($id);

        if (empty($course)) {
            throw $this->createNotFoundException();
        }

        return $this->render('::Admin/Course/dashboard.html.twig', array(
            'course' => $course,
        ));
    }

    private function checkExistingCourse(Course $course, $form)
    {
        $existingCourse = $this->getRepository('AdminBundle:Course')->findBy(array(
            'language' => $course->getLanguage(),
            'name' => $course->getName(),
        ));

        if (!empty($existingCourse)) {
            $form->addError(new FormError(
                sprintf(
                    'A course for language \'%s\' with name \'%s\' already exists',
                    $existingCourse[0]->getLanguage()->getName(),
                    $existingCourse[0]->getName()
                )
            ));

            return $form;
        }

        return null;
    }
}
