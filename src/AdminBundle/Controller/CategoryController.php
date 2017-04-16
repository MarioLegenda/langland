<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\CategoryType;
use API\SharedDataBundle\Repository\CategoryRepository;
use API\SharedDataBundle\Repository\Status;
use ArmorBundle\Controller\MasterSecurityController;
use ArmorBundle\Admin\AdminAuthInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends MasterSecurityController implements AdminAuthInterface
{
    public function createAction(Request $request)
    {
        $form = $this->createForm(CategoryType::class);

        if ($request->getMethod() === 'GET') {
            return $this->render('::Admin/Category/create.html.twig', array(
                'form' => $form->createView()
            ));
        }

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $category = $request->request->get('form')['category'];

                $resultResolver = $this->get('api.shared.category_repository')->create(array('category' => $category));

                if ($resultResolver->getStatus() === Status::FAILURE) {
                    return $this->render('::Admin/Category/create.html.twig', array(
                        'form' => $form->createView(),
                        'internal_error' => sprintf('Category %s could not be created', $category)
                    ));
                }

                return $this->redirectToRoute('category_index');
            }

            return $this->render('::Admin/Category/create.html.twig', array(
                'form' => $form->createView()
            ));
        }

        return $this->createAccessDeniedException();
    }

    public function indexAction()
    {
        $categoryRepo = $this->get('api.shared.category_repository');

        $resultResolver = $categoryRepo->findAllForWorkingLanguage();

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->render('::Admin/Category/index.html.twig', array(
                'internal_error' => 'No categories were found',
            ));
        }

        return $this->render('::Admin/Category/index.html.twig', array(
            'categories' => $resultResolver->getData(),
        ));
    }

    public function editAction(Request $request, $id)
    {
        $categoryRepo = $this->get('api.shared.category_repository');

        $resultResolver = $categoryRepo->findCategoryById(array(
            'category_id' => $id,
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            $this->createNotFoundException();
        }

        $categoryData = $resultResolver->getData();

        if ($request->isMethod('get')) {
            $form = $this->createForm(CategoryType::class, array(
                'category' => $categoryData['category'],
            ));

            return $this->render('::Admin/Category/edit.html.twig', array(
                'form' => $form->createView(),
                'category' => $categoryData,
            ));
        }

        if ($request->isMethod('post')) {
            $category = $request->request->get('form')['category'];
            $data = array('category' => $category);

            $form = $this->createForm(CategoryType::class, $data);

            $form->handleRequest($request);

            if ($form->isValid()) {
                $resultResolver = $categoryRepo->updateCategoryName(array(
                    'category' => $category,
                    'category_id' => $id,
                ));

                if ($resultResolver->getStatus() === Status::FAILURE) {
                    return $this->render('::Admin/Category/edit.html.twig', array(
                        'form' => $form->createView(),
                        'internal_error' => sprintf('There has been an internal error'),
                    ));
                }

                return $this->redirectToRoute('category_index');
            }

            return $this->render('::Admin/Category/edit.html.twig', array(
                'form' => $form->createView(),
                'category' => $categoryData,
            ));
        }

        return $this->createAccessDeniedException();
    }
}
