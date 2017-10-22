<?php

namespace Library\LearningMetadata\Business\Implementation;

use AdminBundle\Entity\Sound;
use Library\LearningMetadata\Presentation\Template\TemplateWrapper;
use Library\LearningMetadata\Repository\Implementation\SoundRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpFoundation\Session\Session;
use Library\Infrastructure\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Library\LearningMetadata\Infrastructure\Form\Type\SoundType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Library\Event\FileUploadEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SoundImplementation
{
    /**
     * @var TemplateWrapper $templateWrapper
     */
    private $templateWrapper;
    /**
     * @var SoundImplementation $soundRepository
     */
    private $soundRepository;
    /**
     * @var FormBuilderInterface $formBuilder
     */
    private $formBuilder;
    /**
     * @var Router $router
     */
    private $router;
    /**
     * @var TraceableEventDispatcher $eventDispatcher
     */
    private $eventDispatcher;
    /**
     * @var Session $session
     */
    private $session;
    /**
     * SoundImplementation constructor.
     * @param SoundRepository $soundRepository
     * @param FormBuilderInterface $formBuilder
     * @param TemplateWrapper $templateWrapper
     * @param Router $router
     * @param Session $session
     */
    public function __construct(
        SoundRepository $soundRepository,
        FormBuilderInterface $formBuilder,
        TemplateWrapper $templateWrapper,
        Router $router,
        TraceableEventDispatcher $eventDispatcher,
        Session $session
    ) {
        $this->soundRepository = $soundRepository;
        $this->formBuilder = $formBuilder;
        $this->templateWrapper = $templateWrapper;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
    }
    /**
     * @return Sound[]
     */
    public function getSounds() : array
    {
        return $this->soundRepository->findAll();
    }
    /**
     * @param array|Sound|null $data
     * @param array $options
     * @return FormInterface
     */
    public function createForm($data = null, array $options = array()) : FormInterface
    {
        return $this->formBuilder->getForm(SoundType::class, $data);
    }
    /**
     * @param string $template
     * @param array $data
     * @return Response
     */
    public function getListPresentation(string $template = null, array $data = null) : Response
    {
        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_listing.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Sounds',
            'sounds' => $this->getSounds(),
            'template' => '/Sound/index.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }
    /**
     * @param string|null $template
     * @param array|null $data
     * @return Response
     */
    public function getCreatePresentation(string $template = null, array $data = null) : Response
    {
        $form = $this->createForm(new Sound());

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Sounds',
            'form' => $form->createView(),
            'template' => '/Sound/create.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }
    /**
     * @param Request $request
     * @param string|null $template
     * @param array|null $data
     * @return Response
     * @throws \Throwable
     */
    public function newSound(Request $request, string $template = null, array $data = null) : Response
    {
        $sound = new Sound();
        $form = $this->createForm($sound);
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST' and $form->isSubmitted() and $form->isValid()) {
            try {
                $this->dispatchEvent(FileUploadEvent::class, $sound);
            } catch (\Throwable $e) {
                // log exception here
                throw $e;
            }

            $this->session->getFlashBag()->add(
                'notice',
                sprintf('Sound created successfully')
            );

            return new RedirectResponse($this->router->generate('admin_sound_create'));
        }

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Sounds',
            'form' => $form->createView(),
            'template' => '/Sound/create.html.twig',
        ];

        return new Response(
            $this->templateWrapper->getTemplate($template, $data),
            400
        );
    }
    /**
     * @param int $id
     * @return Sound
     */
    public function findSound(int $id) : ?Sound
    {
        /** @var Sound $sound */
        $sound = $this->soundRepository->find($id);

        return $sound;
    }
    /**
     * @param string $eventClass
     * @param $entity
     * @return void
     */
    protected function dispatchEvent(string $eventClass, $entity)
    {
        $event = new $eventClass($entity);

        $this->eventDispatcher->dispatch($event::NAME, $event);
    }
}