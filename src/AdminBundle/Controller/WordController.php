<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Word;
use AdminBundle\Form\Type\WordType;
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

                if ($word->hasWordImage()) {
                    $this->uploadWordImage($word);
                }

                $this->removeEmptyTranslations($word);

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

        $wordImage = $this->get('doctrine')->getRepository('AdminBundle:WordImage')->findBy(array(
            'word' => $word,
        ));


        $word->setViewImage((!empty($wordImage)) ? $wordImage[0] : null);

        $form = $this->createForm(WordType::class, $word, array(
            'word' => $word,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                if ($word->hasWordImage()) {
                    $this->uploadWordImage($word);
                }

                $this->removeEmptyTranslations($word);

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

    public function removeAction(Request $request, $id)
    {
        $word = $this->getRepository('AdminBundle:Word')->find($id);

        if (empty($word)) {
            throw $this->createNotFoundException();
        }

        $em = $this->get('doctrine')->getManager();
        $wordImage = $this->getRepository('AdminBundle:WordImage')->findBy(array(
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

    private function uploadWordImage(Word $word)
    {
        $fileUploader = $this->get('admin.file_uploader');

        $fileUploader->uploadImage($word->getWordImage()->getImageFile(), array(
            'repository' => 'AdminBundle:WordImage',
            'field' => 'name',
        ));

        $word->getWordImage()
            ->setName($fileUploader->getFileName())
            ->setOriginalName($fileUploader->getOriginalName())
            ->setTargetDir($fileUploader->getImageDir());
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
