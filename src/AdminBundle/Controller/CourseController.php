<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\CourseType;
use Sylius\Component\Resource\Exception\UpdateHandlingException;
use Symfony\Component\HttpFoundation\Request;
use Library\Event\PrePersistEvent;
use Sylius\Component\Resource\ResourceActions;
use FOS\RestBundle\View\View;

class CourseController extends GenericResourceController implements GenericControllerInterface
{
    public function getListingTitle(): string
    {
        return 'Courses';
    }

    public function getName(): string
    {
        return 'Course';
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
                'course' => $newResource,
            ));

            $this->repository->add($newResource);

            $this->addFlash(
                'notice',
                sprintf('Course created successfully')
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
                'listing_title' => 'Course',
                'template' => '/Course/create.html.twig'
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

            $this->dispatchEvent(PrePersistEvent::class, array(
                'course' => $resource,
            ));

            try {
                $this->resourceUpdateHandler->handle($resource, $configuration, $this->manager);
            } catch (UpdateHandlingException $exception) {
                return $this->redirectHandler->redirectToReferer($configuration);
            }

            $this->addFlash(
                'notice',
                sprintf('Course updated successfully')
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
                'listing_title' => 'Course',
                'template' => '/Course/update.html.twig'
            ])
            ->setTemplate($configuration->getTemplate(ResourceActions::UPDATE . '.html'))
        ;

        return $this->viewHandler->handle($configuration, $view);
    }

    public function manageAction($courseId)
    {
        $course = $this->get('doctrine')->getRepository('AdminBundle:Course')->find($courseId);

        if (empty($course)) {
            throw $this->createNotFoundException();
        }

        return $this->render('::Admin/Course/dashboard.html.twig', array(
            'course' => $course,
        ));
    }
}
