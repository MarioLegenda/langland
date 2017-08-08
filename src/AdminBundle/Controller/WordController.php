<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Word;
use Library\Event\PrePersistEvent;
use Library\Event\PreUpdateEvent;
use AdminBundle\Form\Type\WordType;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Library\Event\FileUploadEvent;
use Sylius\Component\Resource\ResourceActions;
use FOS\RestBundle\View\View;

class WordController extends ResourceController
{
    public function indexAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::INDEX);
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);

        $view = View::create($resources);

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate(ResourceActions::INDEX . '.html'))
                ->setTemplateVar($this->metadata->getPluralName())
                ->setData([
                    'configuration' => $configuration,
                    'metadata' => $this->metadata,
                    'resources' => $resources,
                    $this->metadata->getPluralName() => $resources,
                    'listing_title' => 'Words',
                    'template' => '/Word/index.html.twig'
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }
/*    public function indexAction()
    {
        $words = $this->getRepository('AdminBundle:Word')->findBy(array(), array(
            'id' => 'DESC',
        ));

        return $this->render('::Admin/Word/index.html.twig', array(
            'words' => $words,
        ));
    }*/

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

        if ($form->isSubmitted() and $form->isValid()) {
            $em = $this->get('doctrine')->getManager();

            $this->dispatchEvent(FileUploadEvent::class, $word);

            $this->dispatchEvent(PrePersistEvent::class, array(
                'word' => $word,
            ));

            $em->persist($word);
            $em->flush();

            $this->addFlash(
                'notice',
                sprintf('Word created successfully')
            );

            return $this->redirectToRoute('admin_word_create');
        } else if ($form->isSubmitted() and !$form->isValid()) {
            $response = $this->render('::Admin/Word/create.html.twig', array(
                'form' => $form->createView(),
            ));

            $response->setStatusCode(400);

            return $response;
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

                $this->dispatchEvent(FileUploadEvent::class, $word);

                $this->dispatchEvent(PrePersistEvent::class, array(
                    'word' => $word,
                ));

                $this->dispatchEvent(PreUpdateEvent::class, array(
                    'word' => $word,
                ));

                $em->persist($word);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Word edited successfully')
                );

                return $this->redirectToRoute('admin_word_edit', array(
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

        return $this->redirectToRoute('admin_word_index');
    }
}
