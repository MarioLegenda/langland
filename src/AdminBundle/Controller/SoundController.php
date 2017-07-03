<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Sound;
use Library\Event\FileUploadEvent;
use AdminBundle\Form\Type\SoundType;
use Symfony\Component\HttpFoundation\Request;

class SoundController extends RepositoryController
{
    public function indexAction()
    {
        $sounds = $this->getRepository('AdminBundle:Sound')->findAll();

        return $this->render('::Admin/Sound/index.html.twig', array(
            'sounds' => $sounds,
        ));
    }

    public function createAction(Request $request)
    {
        $sound = new Sound();
        $form = $this->createForm(SoundType::class, $sound);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $this->dispatchEvent(FileUploadEvent::class, $sound);

            $this->addFlash(
                'notice',
                sprintf('Sound(s) created successfully')
            );

            return $this->redirectToRoute('admin_sound_create');
        } else if ($form->isSubmitted() and !$form->isValid()) {
            $response = $this->render('::Admin/Sound/create.html.twig', array(
                'form' => $form->createView(),
            ));

            $response->setStatusCode(400);

            return $response;
        }

        return $this->render('::Admin/Sound/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}