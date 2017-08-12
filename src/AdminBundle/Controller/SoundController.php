<?php

namespace AdminBundle\Controller;

use Library\Event\FileUploadEvent;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Component\Resource\ResourceActions;
use FOS\RestBundle\View\View;

class SoundController extends GenericResourceController implements GenericControllerInterface
{
    /**
     * @return string
     */
    public function getListingTitle(): string
    {
        return 'Sounds';
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Sound';
    }

    public function createAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::CREATE);
        $newResource = $this->newResourceFactory->create($configuration, $this->factory);

        $form = $this->resourceFormFactory->create($configuration, $newResource);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $newResource = $form->getData();

            $this->dispatchEvent(FileUploadEvent::class, $newResource);

            $this->addFlash(
                'notice',
                sprintf('Sound(s) created successfully')
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
                'listing_title' => 'Sounds',
                'template' => '/Sound/create.html.twig'
            ])
            ->setTemplate($configuration->getTemplate(ResourceActions::CREATE . '.html'))
        ;

        return $this->viewHandler->handle($configuration, $view);
    }
}