<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Sound;
use AdminBundle\Event\FileUploadEvent;
use AdminBundle\Form\Type\SoundType;
use Doctrine\ORM\EntityManager;
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

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->dispatchEvent(FileUploadEvent::class, $sound);

                $this->addFlash(
                    'notice',
                    sprintf('Sound(s) created successfully')
                );

                return $this->redirectToRoute('admin_sound_create');
            }
        }

        return $this->render('::Admin/Sound/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}