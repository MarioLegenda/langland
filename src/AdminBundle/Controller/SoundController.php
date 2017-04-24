<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Sound;
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

                $this->uploadSound($sound, $em);

                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Sound(s) created successfully')
                );

                return $this->redirectToRoute('sound_create');
            }
        }

        return $this->render('::Admin/Sound/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function uploadSound(Sound $sound, EntityManager $em)
    {
        $fileUploader = $this->get('admin.file_uploader');

        foreach ($sound->getSoundFile() as $soundFile) {
            $fileUploader->uploadSound($soundFile, array(
                'repository' => 'AdminBundle:Sound',
                'field' => 'name',
            ));

            $newSound = new Sound();

            $newSound
                ->setTargetDir($fileUploader->getSoundDir())
                ->setOriginalName($fileUploader->getOriginalName())
                ->setName($fileUploader->getFileName());

            $em->persist($newSound);
        }
    }
}