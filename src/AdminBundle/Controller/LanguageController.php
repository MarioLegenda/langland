<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Language;
use Library\Event\FileUploadEvent;
use AdminBundle\Form\Type\LanguageType;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sylius\Component\Resource\Exception\UpdateHandlingException;
use Symfony\Component\HttpFoundation\Response;

class LanguageController extends ResourceController
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
                    'listing_title' => 'Languages',
                    'template' => '/Language/index.html.twig'
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

            $this->dispatchEvent(FileUploadEvent::class, $newResource);

            $this->repository->add($newResource);
            $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $newResource);

            $this->addFlash(
                'notice',
                sprintf('Language created successfully')
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
                'listing_title' => 'Create language',
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

        $form = $this->resourceFormFactory->create($configuration, $resource);

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true) && $form->handleRequest($request)->isValid()) {
            $resource = $form->getData();

            /** @var ResourceControllerEvent $event */
            $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::UPDATE, $configuration, $resource);

            if ($event->isStopped() && !$configuration->isHtmlRequest()) {
                throw new HttpException($event->getErrorCode(), $event->getMessage());
            }

            $this->dispatchEvent(FileUploadEvent::class, $resource);

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

                $this->flashHelper->addErrorFlash($configuration, $exception->getFlash());

                return $this->redirectHandler->redirectToReferer($configuration);
            }

            $postEvent = $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $resource);

            if (!$configuration->isHtmlRequest()) {
                $view = $configuration->getParameters()->get('return_content', false) ? View::create($resource, Response::HTTP_OK) : View::create(null, Response::HTTP_NO_CONTENT);

                return $this->viewHandler->handle($configuration, $view);
            }

            $this->addFlash(
                'notice',
                sprintf('Language updated successfully')
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
                'listing_title' => 'Create language',
                'template' => '/Language/update.html.twig'
            ])
            ->setTemplate($configuration->getTemplate(ResourceActions::UPDATE . '.html'))
        ;

        return $this->viewHandler->handle($configuration, $view);
    }

    /**
     * @param string $eventClass
     * @param $entity
     * @return void
     */
    protected function dispatchEvent(string $eventClass, $entity)
    {
        $eventDispatcher = $this->get('event_dispatcher');

        $event = new $eventClass($entity);

        $eventDispatcher->dispatch($event::NAME, $event);
    }

    /*    public function indexAction()
        {
            $languages = $this->getRepository('AdminBundle:Language')->findAll();

            return $this->render('::Admin/Template/Panel/_listing.html.twig', array(
                'languages' => $languages,
                'listing_title' => 'Languages',
                'template' => '/Language/_index.html.twig'
            ));
        }

        public function createAction(Request $request)
        {
            $language = new Language();
            $form = $this->createForm(LanguageType::class, $language);

            $form->handleRequest($request);

            if ($form->isSubmitted() and $form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->dispatchEvent(FileUploadEvent::class, $language);

                $em->persist($language);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Language created successfully')
                );

                return $this->redirectToRoute('admin_language_create');
            } else if ($form->isSubmitted() and !$form->isValid()) {
                $response = $this->render('::Admin/Template/Panel/_action.html.twig', array(
                    'form' => $form->createView(),
                    'listing_title' => 'Create language',
                    'template' => '/Language/_create.html.twig'
                ));

                $response->setStatusCode(400);

                return $response;
            }

            return $this->render('::Admin/Template/Panel/_action.html.twig', array(
                'form' => $form->createView(),
                'listing_title' => 'Create language',
                'template' => '/Language/_create.html.twig'
            ));
        }

        public function editAction(Request $request, $id)
        {
            $language = $this->getRepository('AdminBundle:Language')->find($id);

            if (!$language instanceof Language) {
                throw $this->createNotFoundException();
            }

            $image = $this->getRepository('AdminBundle:Image')->findOneBy(array(
                'language' => $language,
            ));

            if (!empty($image)) {
                $language->setImage($image);
            }

            $form = $this->createForm(LanguageType::class, $language);

            $form->handleRequest($request);

            if ($form->isSubmitted() and $form->isValid()) {
                $em = $this->get('doctrine')->getManager();

                $this->dispatchEvent(FileUploadEvent::class, $language);

                $em->persist($language);
                $em->flush();

                $this->addFlash(
                    'notice',
                    sprintf('Language edited successfully')
                );

                return $this->redirectToRoute('admin_language_edit', array(
                    'id' => $id,
                ));
            } else if ($form->isSubmitted() and !$form->isValid()) {
                $response = $this->render('::Admin/Template/Panel/_action.html.twig', array(
                    'form' => $form->createView(),
                    'listing_title' => 'Create language',
                    'template' => '/Language/_edit.html.twig'
                ));

                $response->setStatusCode(400);

                return $response;
            }


            return $this->render('::Admin/Template/Panel/_action.html.twig', array(
                'form' => $form->createView(),
                'language' => $language,
                'listing_title' => 'Create language',
                'template' => '/Language/_edit.html.twig'
            ));
        }*/
}
