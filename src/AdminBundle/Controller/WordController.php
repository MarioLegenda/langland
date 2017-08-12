<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Word;
use Library\Event\PrePersistEvent;
use Symfony\Component\HttpFoundation\Request;
use Library\Event\FileUploadEvent;
use Sylius\Component\Resource\ResourceActions;
use FOS\RestBundle\View\View;
use Sylius\Component\Resource\Exception\UpdateHandlingException;
use Library\Event\PreUpdateEvent;

class WordController extends GenericResourceController implements GenericControllerInterface
{
    public function getName() : string
    {
        return 'Word';
    }

    public function getListingTitle(): string
    {
        return 'Words';
    }

    public function createAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::CREATE);
        $newResource = $this->newResourceFactory->create($configuration, $this->factory);

        $form = $this->resourceFormFactory->create($configuration, $newResource);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $newResource = $form->getData();

            if ($configuration->hasStateMachine()) {
                $this->stateMachine->apply($configuration, $newResource);
            }

            $this->dispatchEvent(FileUploadEvent::class, $newResource);

            $this->dispatchEvent(PrePersistEvent::class, array(
                'word' => $newResource,
            ));

            $this->repository->add($newResource);

            $this->addFlash(
                'notice',
                sprintf('Word created successfully')
            );

            return $this->redirectHandler->redirectToResource($configuration, $newResource);
        }

        $view = View::create()
            ->setData([
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $newResource,
                $this->metadata->getName() => $newResource,
                'form' => $form->createView(),
                'listing_title' => 'Word',
                'template' => '/Word/create.html.twig'
            ])
            ->setTemplate($configuration->getTemplate(ResourceActions::CREATE . '.html'))
        ;

        return $this->viewHandler->handle($configuration, $view);
    }

/*    public function createAction(Request $request)
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
    }*/

/*    public function editAction(Request $request, $id)
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
    }*/

    public function updateAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        $resource = $this->findOr404($configuration);

        $this->setImageIfExists($resource);

        $form = $this->resourceFormFactory->create($configuration, $resource);

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true) && $form->handleRequest($request)->isValid()) {
            $resource = $form->getData();

            $this->dispatchEvent(FileUploadEvent::class, $resource);

            $this->dispatchEvent(PrePersistEvent::class, array(
                'word' => $resource,
            ));

            $this->dispatchEvent(PreUpdateEvent::class, array(
                'word' => $resource,
            ));

            try {
                $this->resourceUpdateHandler->handle($resource, $configuration, $this->manager);
            } catch (UpdateHandlingException $exception) {
                return $this->redirectHandler->redirectToReferer($configuration);
            }

            $this->addFlash(
                'notice',
                sprintf('Word updated successfully')
            );

            return $this->redirectHandler->redirectToResource($configuration, $resource);
        }

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle($configuration, View::create($form, Response::HTTP_BAD_REQUEST));
        }

        $view = View::create()
            ->setData([
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $resource,
                $this->metadata->getName() => $resource,
                'form' => $form->createView(),
                'listing_title' => 'Word',
                'template' => '/Word/update.html.twig'
            ])
            ->setTemplate($configuration->getTemplate(ResourceActions::UPDATE . '.html'))
        ;

        return $this->viewHandler->handle($configuration, $view);
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

    private function setImageIfExists(Word $word)
    {
        $image = $this->get('doctrine')->getRepository('AdminBundle:Image')->findBy(array(
            'word' => $word,
        ));

        if (!empty($image)) {
            $word->setImage($image[0]);
        }
    }
}
