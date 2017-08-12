<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Language;
use Library\Event\FileUploadEvent;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Component\Resource\Exception\UpdateHandlingException;

class LanguageController extends GenericResourceController implements GenericControllerInterface
{
    /**
     * @return string
     */
    public function getListingTitle(): string
    {
        return 'Languages';
    }
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Language';
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

            $this->repository->add($newResource);

            $this->addFlash(
                'notice',
                sprintf('Language created successfully')
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
                'listing_title' => 'Languages',
                'template' => '/Language/create.html.twig'
            ])
            ->setTemplate($configuration->getTemplate(ResourceActions::CREATE . '.html'))
        ;

        return $this->viewHandler->handle($configuration, $view);
    }

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

            try {
                $this->resourceUpdateHandler->handle($resource, $configuration, $this->manager);
            } catch (UpdateHandlingException $exception) {
                if (!$configuration->isHtmlRequest()) {
                    return $this->viewHandler->handle(
                        $configuration,
                        View::create($form, $exception->getApiResponseCode())
                    );
                }

                return $this->redirectHandler->redirectToReferer($configuration);
            }

            $this->addFlash(
                'notice',
                sprintf('Language updated successfully')
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
                'listing_title' => 'Languages',
                'template' => '/Language/update.html.twig'
            ])
            ->setTemplate($configuration->getTemplate(ResourceActions::UPDATE . '.html'))
        ;

        return $this->viewHandler->handle($configuration, $view);
    }

    private function setImageIfExists(Language $language)
    {
        $image = $this->get('doctrine')->getRepository('AdminBundle:Image')->findBy(array(
            'language' => $language,
        ));

        if (!empty($image)) {
            $language->setImage($image[0]);
        }
    }
}
