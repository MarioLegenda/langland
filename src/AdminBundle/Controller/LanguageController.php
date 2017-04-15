<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\LanguageType;
use API\SharedDataBundle\Repository\Status;
use ArmorBundle\Admin\AdminAuthInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class LanguageController extends Controller implements AdminAuthInterface
{
    public function indexAction()
    {
        $languageRepo = $this->get('api.shared.language_repository');

        $resultResolver = $languageRepo->findAll();

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->render('::Admin/Language/index.html.twig', array(
                'internal_error' => 'No languages have been added'
            ));
        }

        return $this->render('::Admin/Language/index.html.twig', array(
            'languages' => $resultResolver->getData(),
        ));
    }

    public function createAction(Request $request)
    {
        $form = $this->createForm(LanguageType::class);

        if ($request->getMethod() === 'GET') {
            return $this->render('::Admin/Language/create.html.twig', array(
                'form' => $form->createView()
            ));
        }

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $language = $request->request->get('language')['language'];

                $resultResolver = $this->get('api.shared.language_repository')
                    ->create(array('language' => $language));

                if ($resultResolver->getStatus() === Status::FAILURE) {
                    return $this->render('::Admin/Language/create.html.twig', array(
                        'form' => $form->createView(),
                        'internal_error' => sprintf('Language %s could not be created', $language)
                    ));
                }

                return $this->redirectToRoute('language_index');
            }

            return $this->render('::Admin/Language/create.html.twig', array(
                'form' => $form->createView()
            ));
        }

        return $this->createAccessDeniedException();
    }

    public function editAction(Request $request, $id)
    {
        $languageRepo = $this->get('api.shared.language_repository');

        $resultResolver = $languageRepo->findLanguageById(array(
            'language_id' => $id,
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            $this->createNotFoundException();
        }

        $languageData = $resultResolver->getData();

        if ($request->isMethod('get')) {
            $form = $this->createForm(LanguageType::class, array(
                'language' => $languageData['language'],
            ));

            return $this->render('::Admin/Language/edit.html.twig', array(
                'form' => $form->createView(),
                'language' => $languageData,
            ));
        }

        if ($request->isMethod('post')) {
            $language = $request->request->get('language')['language'];
            $data = array('language' => $language);


            $form = $this->createForm(LanguageType::class, $data);

            $form->handleRequest($request);

            if ($form->isValid()) {
                $resultResolver = $languageRepo->updateLanguageName(array(
                    'language' => $language,
                    'language_id' => $id,
                ));

                if ($resultResolver->getStatus() === Status::FAILURE) {
                    return $this->render('::Admin/Language/edit.html.twig', array(
                        'form' => $form->createView(),
                        'internal_error' => sprintf('There has been an internal error'),
                    ));
                }

                return $this->redirectToRoute('language_index');
            }

            return $this->render('::Admin/Language/edit.html.twig', array(
                'form' => $form->createView(),
                'language' => $languageData,
            ));
        }

        return $this->createAccessDeniedException();
    }

    public function findLanguageByIdAction()
    {
        return parent::findLanguageByIdAction($request);
    }

    public function findAllAction()
    {
        return parent::findAllAction();
    }

    public function updateWorkingLanguageAction()
    {
        return parent::updateWorkingLanguageAction($request);
    }
}
