<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Language;
use AdminBundle\Form\Type\LanguageType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

                $this->tryUploadLanguageIcon($language);

                if ($language->getImage()->getImageFile() instanceof UploadedFile) {
                    $language->getImage()->setLanguage($language);
                    $em->persist($language->getImage());
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

        $image = $this->getRepository('AdminBundle:Image')->findBy(array(
            'language' => $language,
        ));

        if (!empty($image)) {
            $language->setImage($image[0]);
        }

        if (!$language instanceof Language) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(LanguageType::class, $language);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->tryRemovePreviousIcon($language);
                $this->tryUploadLanguageIcon($language);

                if ($language->getImage()->getImageFile() instanceof UploadedFile) {
                    $language->getImage()->setLanguage($language);
                    $em->persist($language->getImage());
                }

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

    private function tryUploadLanguageIcon(Language $language)
    {
        if ($language->getImage()->getImageFile() instanceof UploadedFile) {
            $fileUploader = $this->get('admin.file_uploader');

            $fileUploader->uploadImage($language->getImage()->getImageFile(), array(
                'repository' => 'AdminBundle:Image',
                'field' => 'name',
            ));

            $data = $fileUploader->getData();

            $language->getImage()
                ->setName($data['fileName'])
                ->setOriginalName($data['originalName'])
                ->setTargetDir($data['targetDir'])
                ->setFullPath($data['fullPath']);
        }
    }

    private function tryRemovePreviousIcon(Language $language)
    {
        if ($language->getImage()->getImageFile() instanceof UploadedFile) {
            $dbImage = $this->getRepository('AdminBundle:Image')->findBy(array(
                'language' => $language,
            ));

            if (!empty($dbImage)) {
                $image = $dbImage[0];

                unlink($image->getTargetDir().'/'.$image->getName());
            }
        }
    }
}
