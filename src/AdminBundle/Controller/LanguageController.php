<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Language;
use Library\Event\FileUploadEvent;
use AdminBundle\Form\Type\LanguageType;
use Symfony\Component\HttpFoundation\Request;

class LanguageController extends RepositoryController
{
    public function indexAction()
    {
        $languages = $this->getRepository('AdminBundle:Language')->findAll();

        return $this->render('::Admin/Template/Panel/Listing/_listing.html.twig', array(
            'languages' => $languages,
            'listing_title' => 'Languages',
            'template' => '/Language/index.html.twig'
        ));
    }

    public function createAction(Request $request)
    {
        $language = new Language();
        $form = $this->createForm(LanguageType::class, $language);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->get('doctrine')->getManager();

            $this->dispatchEvent(FileUploadEvent::class, $language);

            $em->persist($language);
            $em->flush();

            $this->addFlash(
                'notice',
                sprintf('Language created successfully')
            );

            return $this->redirectToRoute('admin_language_create');
        } else if ($form->isSubmitted() and !$form->isValid()) {
            $response = $this->render('::Admin/Language/create.html.twig', array(
                'form' => $form->createView(),
            ));

            $response->setStatusCode(400);

            return $response;
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

        $image = $this->getRepository('AdminBundle:Image')->findOneBy(array(
            'language' => $language,
        ));

        if (!empty($image)) {
            $language->setImage($image);
        }

        $form = $this->createForm(LanguageType::class, $language);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->get('doctrine')->getManager();

            $this->dispatchEvent(FileUploadEvent::class, $language);

            $em->persist($language);
            $em->flush();

            $this->addFlash(
                'notice',
                sprintf('Language edited successfully')
            );

            return $this->redirectToRoute('admin_language_edit', array(
                'id' => $id,
            ));
        } else if ($form->isSubmitted() and !$form->isValid()) {
            $response = $this->render('::Admin/Language/create.html.twig', array(
                'form' => $form->createView(),
            ));

            $response->setStatusCode(400);

            return $response;
        }


        return $this->render('::Admin/Language/edit.html.twig', array(
            'form' => $form->createView(),
            'language' => $language,
        ));
    }
}
