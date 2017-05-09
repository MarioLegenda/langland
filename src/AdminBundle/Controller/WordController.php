<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Word;
use AdminBundle\Form\Type\WordType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class WordController extends RepositoryController
{
    public function indexAction()
    {
        $words = $this->getRepository('AdminBundle:Word')->findAll();

        return $this->render('::Admin/Word/index.html.twig', array(
            'words' => $words,
        ));
    }

    public function createAction(Request $request)
    {
        $language = $this->getRepository('AdminBundle:Language')->find(1);

        if (empty($language)) {
            return $this->render('::Admin/Word/create.html.twig', array(
                'no_language' => true,
            ));
        }

        $word = new Word();
        $form = $this->createForm(WordType::class, $word, array(
            'word' => $word,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->tryUploadImage($word);

                $this->removeEmptyTranslations($word);

                if ($word->getImage()->getImageFile() instanceof UploadedFile) {
                    $word->getImage()->setWord($word);
                    $em->persist($word->getImage());
                }

                $em->persist($word);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Word created successfully')
                );

                return $this->redirectToRoute('word_create');
            }
        }

        return $this->render('::Admin/Word/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $id)
    {
        $word = $this->getRepository('AdminBundle:Word')->find($id);

        if (empty($word)) {
            throw $this->createNotFoundException();
        }

        $wordImage = $this->get('doctrine')->getRepository('AdminBundle:Image')->findBy(array(
            'word' => $word,
        ));

        if (!empty($wordImage)) {
            $word->setImage($wordImage[0]);
        }

        $form = $this->createForm(WordType::class, $word, array(
            'word' => $word,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->removeEmptyTranslations($word);
                $this->tryRemovePreviousImage($word);
                $this->tryUploadImage($word);

                if ($word->getImage()->getImageFile() instanceof UploadedFile) {
                    $word->getImage()->setWord($word);
                    $em->persist($word->getImage());
                }

                $em->persist($word);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Word edited successfully')
                );

                return $this->redirectToRoute('word_edit', array(
                    'id' => $word->getId(),
                ));
            }
        }

        return $this->render('::Admin/Word/edit.html.twig', array(
            'form' => $form->createView(),
            'word' => $word,
        ));
    }

    public function removeAction($id)
    {
        $word = $this->getRepository('AdminBundle:Word')->find($id);

        if (empty($word)) {
            throw $this->createNotFoundException();
        }

        $em = $this->get('doctrine')->getManager();
        $wordImage = $this->getRepository('AdminBundle:Image')->findBy(array(
            'word' => $word,
        ));

        if (!empty($wordImage)) {
            $wordImage = $wordImage[0];

            $fileName = $wordImage->getName();
            $targetDir = realpath($wordImage->getTargetDir());
            $fullFileName = $targetDir.'/'.$fileName;

            if (file_exists($fullFileName)) {
                unlink($fullFileName);
            }

            $em->remove($wordImage);
        }

        $em->remove($word);

        $em->flush();

        return $this->redirectToRoute('word_index');
    }

    private function tryUploadImage(Word $word)
    {
        if ($word->getImage()->getImageFile() instanceof UploadedFile) {
            $fileUploader = $this->get('admin.file_uploader');

            $fileUploader->uploadImage($word->getImage()->getImageFile(), array(
                'repository' => 'AdminBundle:Image',
                'field' => 'name',
                'resize' => array(
                    'width' => 250,
                    'height' => 250,
                ),
            ));

            $fileData = $fileUploader->getData();

            $word->getImage()
                ->setName($fileData['fileName'])
                ->setOriginalName($fileData['originalName'])
                ->setTargetDir($fileData['targetDir'])
                ->setFullPath($fileData['fullPath']);
        }
    }

    private function tryRemovePreviousImage(Word $word)
    {
        if ($word->getImage()->getImageFile() instanceof UploadedFile) {
            $dbImage = $this->getRepository('AdminBundle:Image')->findBy(array(
                'word' => $word,
            ));

            if (!empty($dbImage)) {
                $image = $dbImage[0];

                unlink($image->getTargetDir().'/'.$image->getName());
            }
        }
    }

    private function removeEmptyTranslations(Word $word)
    {
        foreach ($word->getTranslations() as $translation) {
            if (is_null($translation->getName())) {
                $word->removeTranslation($translation);
            }
        }
    }
}
