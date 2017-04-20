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
            return array(
                'template' => '::Admin/Language/index.html.twig',
                'data' => array(
                    'internal_error' => 'No languages have been added'
                )
            );
        }

        $data = $resultResolver->getData();

        if (is_string(array_keys($data)[0])) {
            $data = array($data);
        }

        return array(
            'template' => '::Admin/Language/index.html.twig',
            'data' => array(
                'languages' => $data,
            ),
        );
    }

    public function createAction(Request $request)
    {
        $form = $this->createForm(LanguageType::class);

        if ($request->getMethod() === 'GET') {
            return array(
                'template' => '::Admin/Language/create.html.twig',
                'data' => array(
                    'form' => $form->createView(),
                ),
            );
        }

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $language = $request->request->get('language')['language'];

                $resultResolver = $this->get('api.shared.language_repository')
                    ->create(array('language' => $language));

                if ($resultResolver->getStatus() === Status::FAILURE) {
                    return array(
                        'template' => '::Admin/Language/create.html.twig',
                        'data' => array(
                            'internal_error' => sprintf('Language %s could not be created', $language),
                        ),
                    );
                }

                return $this->redirectToRoute('language_index');
            }

            return array(
                'template' => '::Admin/Language/create.html.twig',
                'data' => array(
                    'form' => $form->createView(),
                )
            );
        }

        throw $this->createAccessDeniedException();
    }

    public function editAction(Request $request, $id)
    {
        $languageRepo = $this->get('api.shared.language_repository');

        $resultResolver = $languageRepo->findLanguageById(array(
            'language_id' => $id,
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            throw $this->createNotFoundException();
        }

        $languageData = $resultResolver->getData();

        if ($request->isMethod('get')) {
            $form = $this->createForm(LanguageType::class, array(
                'language' => $languageData['language'],
            ));

            return array(
                'template' => '::Admin/Language/edit.html.twig',
                'data' => array(
                    'form' => $form->createView(),
                    'language' => $languageData,
                ),
            );
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
                    return array(
                        'template' => '::Admin/Language/edit.html.twig',
                        'data' => array(
                            'internal_error' => sprintf('There has been an internal error'),
                        )
                    );
                }

                return $this->redirectToRoute('language_index');
            }

            return array(
                'template' => '::Admin/Language/edit.html.twig',
                'data' => array(
                    'form' => $form->createView(),
                    'language' => $languageData,
                )
            );
        }

        throw $this->createAccessDeniedException();
    }

    public function updateWorkingLanguageAction(Request $request, $id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $data = array(
            'user_id' => $user->getId(),
            'language_id' => $id
        );

        $this->get('api.shared.language_repository')->updateWorkingLanguage($data);

        return $this->redirectToRoute('language_index');
    }
}
