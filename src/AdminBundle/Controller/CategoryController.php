<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Category;
use AdminBundle\Form\Type\CategoryType;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Component\Resource\ResourceActions;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Sylius\Component\Resource\Exception\UpdateHandlingException;

class CategoryController extends ResourceController
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
                    'listing_title' => 'Categories',
                    'template' => '/Category/index.html.twig'
                ])
            ;
        }

        return $this->viewHandler->handle($configuration, $view);
    }

    public function createAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::CREATE);
        $newResource = $this->newResourceFactory->create($configuration, $this->factory);

        $form = $this->resourceFormFactory->create($configuration, $newResource);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $newResource = $form->getData();

            $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $newResource);

            if ($event->isStopped() && !$configuration->isHtmlRequest()) {
                throw new HttpException($event->getErrorCode(), $event->getMessage());
            }
            if ($event->isStopped()) {
                $this->flashHelper->addFlashFromEvent($configuration, $event);

                return $this->redirectHandler->redirectToIndex($configuration, $newResource);
            }

            if ($configuration->hasStateMachine()) {
                $this->stateMachine->apply($configuration, $newResource);
            }

            $this->repository->add($newResource);
            $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $newResource);

            $this->addFlash(
                'notice',
                sprintf('Category created successfully')
            );

            if (!$configuration->isHtmlRequest()) {
                return $this->viewHandler->handle($configuration, View::create($newResource, Response::HTTP_CREATED));
            }

            return $this->redirectHandler->redirectToResource($configuration, $newResource);
        }

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle($configuration, View::create($form, Response::HTTP_BAD_REQUEST));
        }

        $this->eventDispatcher->dispatchInitializeEvent(ResourceActions::CREATE, $configuration, $newResource);

        $view = View::create()
            ->setData([
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $newResource,
                $this->metadata->getName() => $newResource,
                'form' => $form->createView(),
                'listing_title' => 'Create a category',
                'template' => '/Category/create.html.twig'
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

        $form = $this->resourceFormFactory->create($configuration, $resource);

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true) && $form->handleRequest($request)->isValid()) {
            $resource = $form->getData();

            /** @var ResourceControllerEvent $event */
            $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $resource);

            if ($event->isStopped() && !$configuration->isHtmlRequest()) {
                throw new HttpException($event->getErrorCode(), $event->getMessage());
            }

            if ($event->isStopped()) {
                $this->flashHelper->addFlashFromEvent($configuration, $event);

                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                return $this->redirectHandler->redirectToResource($configuration, $resource);
            }

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

            $postEvent = $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $resource);

            if (!$configuration->isHtmlRequest()) {
                $view = $configuration->getParameters()->get('return_content', false) ? View::create($resource, Response::HTTP_OK) : View::create(null, Response::HTTP_NO_CONTENT);

                return $this->viewHandler->handle($configuration, $view);
            }

            $this->addFlash(
                'notice',
                sprintf('Category updated successfully')
            );

            if ($postEvent->hasResponse()) {
                return $postEvent->getResponse();
            }

            return $this->redirectHandler->redirectToResource($configuration, $resource);
        }

        if (!$configuration->isHtmlRequest()) {
            return $this->viewHandler->handle($configuration, View::create($form, Response::HTTP_BAD_REQUEST));
        }

        $this->eventDispatcher->dispatchInitializeEvent(ResourceActions::UPDATE, $configuration, $resource);

        $view = View::create()
            ->setData([
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $resource,
                $this->metadata->getName() => $resource,
                'form' => $form->createView(),
                'listing_title' => 'Edit category',
                'template' => '/Category/update.html.twig'
            ])
            ->setTemplate($configuration->getTemplate(ResourceActions::UPDATE . '.html'))
        ;

        return $this->viewHandler->handle($configuration, $view);
    }
}
