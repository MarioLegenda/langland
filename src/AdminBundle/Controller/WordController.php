<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\WordType;
use API\SharedDataBundle\Repository\Status;
use ArmorBundle\Admin\AdminAuthInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class WordController extends Controller implements AdminAuthInterface
{
    public function createAction(Request $request)
    {
        $form = $this->createForm(WordType::class);

        if ($request->isMethod('get')) {
            return array(
                'template' => '::Admin/Word/create.html.twig',
                'data' => array(
                    'form' => $form->createView(),
                ),
            );
        }

        if ($request->isMethod('post')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $wordRepository = $this->get('api.shared.word_repository');
                $data = $request->request->get('form');
                $image = $request->files->get('form')['image'];

                $data['image'] = $image;
                $data['user_id'] = $this->getUser()->getId();

                $resultResolver = $wordRepository->create($data);

                return $this->redirectToRoute('word_index');
            }

            return array(
                'template' => '::Admin/Word/create.html.twig',
                'data' => array(
                    'form' => $form->createView(),
                ),
            );
        }

        throw $this->createAccessDeniedException();
    }

    public function editAction(Request $request, $id)
    {
        $wordRepo = $this->get('api.shared.word_repository');

        $resultResolver = $wordRepo->findSingleWordByWorkingLanguageComplex(array(
            'user_id' => $this->getUser()->getId(),
            'word_id' => $id,
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            throw $this->createNotFoundException();
        }

        if ($request->isMethod('get')) {
            $form = $this->createForm(WordType::class, $resultResolver->getData());

            return array(
                'template' => '::Admin/Word/edit.html.twig',
                'data' => array(
                    'form' => $form->createView()
                )
            );
        }
    }

    public function indexAction()
    {
        $wordRepo = $this->get('api.shared.word_repository');

        $resultResolver = $wordRepo->findAllWordsByWorkingLanguageSimple(array(
            'user_id' => $this->getUser()->getId(),
        ));

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return array(
                'template' => '::Admin/Word/index.html.twig',
                'data' => array(
                    'internal_error' => true,
                )
            );
        }

        return array(
            'template' => '::Admin/Word/index.html.twig',
            'data' => array(
                'words' => $resultResolver->getData()['select_all_words'],
            ),
        );
    }
}
