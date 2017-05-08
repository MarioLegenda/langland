<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Language;
use AdminBundle\Form\Type\LanguageType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class LanguageController extends RepositoryController
{
    public function indexAction()
    {
        $languages = $this->getRepository('AdminBundle:Language')->findAll();

        return $this->render('::Admin/Language/index.html.twig', array(
            'languages' => $languages,
        ));
    }

    public function createAction(Request $request)
    {
        $language = new Language();
        $form = $this->createForm(LanguageType::class, $language);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $potentionalLanguage = $this->getRepository('AdminBundle:Language')->findBy(array(
                    'name' => $language->getName(),
                ));

                if (!empty($potentionalLanguage)) {
                    $form->addError(new FormError(
                        sprintf('Language with name \'%s\' already exists', $language->getName())
                    ));

                    return $this->render('::Admin/Language/create.html.twig', array(
                        'form' => $form->createView(),
                    ));
                }

                $em->persist($language);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Language created successfully')
                );

                return $this->redirectToRoute('language_create');
            }
        }

        return $this->render('::Admin/Language/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $id)
    {
        $language = $this->getRepository('AdminBundle:Language')->find($id);

        if (!$language instanceof Language) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(LanguageType::class, $language);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $em->persist($language);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Language edited successfully')
                );

                return $this->redirectToRoute('language_edit', array(
                    'id' => $id,
                ));
            }
        }

        return $this->render('::Admin/Language/edit.html.twig', array(
            'form' => $form->createView(),
            'language' => $language,
        ));
    }
}
