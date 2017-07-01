<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Category;
use AdminBundle\Form\Type\CategoryType;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends RepositoryController
{
    public function indexAction()
    {
        $categories = $this->getRepository('AdminBundle:Category')->findAll();

        return $this->render('::Admin/Category/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    public function createAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->get('doctrine')->getManager();

            $em->persist($category);
            $em->flush();

            $this->addFlash(
                'notice',
                sprintf('Category created successfully')
            );

            return $this->redirectToRoute('admin_category_create');
        } else if ($form->isSubmitted() and !$form->isValid()) {
            $response = $this->render('::Admin/Category/create.html.twig', array(
                'form' => $form->createView(),
            ));

            $response->setStatusCode(400);

            return $response;
        }

        return $this->render('::Admin/Category/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $id)
    {
        $category = $this->getRepository('AdminBundle:Category')->find($id);

        if (!$category instanceof Category) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->get('doctrine')->getManager();

            $em->persist($category);
            $em->flush();

            $this->addFlash(
                'notice',
                sprintf('Category edited successfully')
            );

            return $this->redirectToRoute('admin_category_edit', array(
                'id' => $id,
            ));
        } else if ($form->isSubmitted() and !$form->isValid()) {
            $response = $this->render('::Admin/Category/edit.html.twig', array(
                'form' => $form->createView(),
                'category' => $category,
            ));

            $response->setStatusCode(400);

            return $response;
        }

        return $this->render('::Admin/Category/edit.html.twig', array(
            'form' => $form->createView(),
            'category' => $category,
        ));
    }
}
