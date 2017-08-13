<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\LanguageInfo;
use Library\Event\PrePersistEvent;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Component\Resource\ResourceActions;
use FOS\RestBundle\View\View;
use Sylius\Component\Resource\Exception\UpdateHandlingException;

class LanguageInfoController extends GenericResourceController implements GenericControllerInterface
{
    public function getListingTitle(): string
    {
        return 'Language infos';
    }

    public function getName(): string
    {
        return 'LanguageInfo';
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

            $this->dispatchEvent(PrePersistEvent::class, array(
                'languageInfo' => $newResource,
            ));

            $this->repository->add($newResource);

            $this->addFlash(
                'notice',
                sprintf('Language info created successfully')
            );

            return $this->redirectHandler->redirectToResource($configuration, $newResource);
        }

        $this->eventDispatcher->dispatchInitializeEvent(ResourceActions::CREATE, $configuration, $newResource);

        $view = View::create()
            ->setData([
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $newResource,
                $this->metadata->getName() => $newResource,
                'form' => $form->createView(),
                'listing_title' => 'Create language info',
                'template' => '/LanguageInfo/create.html.twig'
            ])
            ->setTemplate($configuration->getTemplate(ResourceActions::CREATE . '.html'))
        ;

        if ($form->isSubmitted() and !$form->isValid()) {
            $view->setStatusCode(400);
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    public function updateAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::UPDATE);
        $resource = $this->findOr404($configuration);

        $form = $this->resourceFormFactory->create($configuration, $resource);

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true) && $form->handleRequest($request)->isValid()) {
            $resource = $form->getData();

            $this->dispatchEvent(PrePersistEvent::class, array(
                'languageInfo' => $resource,
            ));

            $this->removeDeletetedTexts($resource);

            try {
                $this->resourceUpdateHandler->handle($resource, $configuration, $this->manager);
            } catch (UpdateHandlingException $exception) {
                $this->flashHelper->addErrorFlash($configuration, $exception->getFlash());

                return $this->redirectHandler->redirectToReferer($configuration);
            }

            $this->addFlash(
                'notice',
                sprintf('Language info updated successfully')
            );

            return $this->redirectHandler->redirectToResource($configuration, $resource);
        }

        $view = View::create()
            ->setData([
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $resource,
                $this->metadata->getName() => $resource,
                'form' => $form->createView(),
                'listing_title' => 'Edit language info',
                'template' => '/LanguageInfo/update.html.twig'
            ])
            ->setTemplate($configuration->getTemplate(ResourceActions::UPDATE . '.html'))
        ;

        if ($form->isSubmitted() and !$form->isValid()) {
            $view->setStatusCode(400);
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    public function removeAction(Request $request, $id)
    {
        $languageInfo = $this->get('doctrine')->getRepository('AdminBundle:LanguageInfo')->find($id);

        $em = $this->get('doctrine')->getManager();

        $em->remove($languageInfo);
        $em->flush();

        $this->addFlash(
            'notice',
            sprintf('Language info deleted successfully')
        );

        return $this->redirectToRoute('admin_language_info_index');
    }

    private function removeDeletetedTexts(LanguageInfo $languageInfo)
    {
        $em = $this->get('doctrine')->getManager();

        $dbLanguageInfoTexts = $em->getRepository('AdminBundle:LanguageInfoText')->findBy(array(
            'languageInfo' => $languageInfo,
        ));

        foreach ($dbLanguageInfoTexts as $text) {
            if (!$languageInfo->hasLanguageInfoText($text)) {
                $em->remove($text);
            }
        }
    }
}