<?php

namespace Library\LearningMetadata\Business\Implementation;

use AdminBundle\Entity\Language;
use Library\Infrastructure\Form\FormBuilderInterface;
use Library\LearningMetadata\Infrastructure\Form\Type\LanguageType;
use Library\LearningMetadata\Presentation\Template\TemplateWrapper;
use Library\LearningMetadata\Repository\Implementation\ImageRepository;
use Library\LearningMetadata\Repository\Implementation\LanguageRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Library\Event\FileUploadEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Router;

class LanguageImplementation
{
    /**
     * @var TemplateWrapper $templateWrapper
     */
    private $templateWrapper;
    /**
     * @var LanguageRepository $languageRepository
     */
    private $languageRepository;
    /**
     * @var ImageRepository $imageRepository
     */
    private $imageRepository;
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
     * LanguageImplementation constructor.
     * @param LanguageRepository $languageRepository
     * @param FormBuilderInterface $formBuilder
     * @param TemplateWrapper $templateWrapper
     * @param Router $router
     * @param Session $session
     */
    public function __construct(
        LanguageRepository $languageRepository,
        ImageRepository $imageRepository,
        FormBuilderInterface $formBuilder,
        TemplateWrapper $templateWrapper,
        Router $router,
        TraceableEventDispatcher $eventDispatcher,
        Session $session
    ) {
        $this->languageRepository = $languageRepository;
        $this->imageRepository = $imageRepository;
        $this->formBuilder = $formBuilder;
        $this->templateWrapper = $templateWrapper;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
    }
    /**
     * @return Language[]
     */
    public function getLanguages() : array
    {
        return $this->languageRepository->findAll();
    }
    /**
     * @param array|Language|null $data
     * @param array $options
     * @return FormInterface
     */
    public function createForm($data = null, array $options = array()) : FormInterface
    {
        return $this->formBuilder->getForm(LanguageType::class, $data);
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
            'listing_title' => 'Languages',
            'languages' => $this->getLanguages(),
            'template' => '/Language/index.html.twig',
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
        $form = $this->createForm(new Language());

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Languages',
            'form' => $form->createView(),
            'template' => '/Language/create.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }

    public function newLanguage(Request $request, string $template = null, array $data = null) : Response
    {
        $language = new Language();
        $form = $this->createForm($language);
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST' and $form->isSubmitted() and $form->isValid()) {
            $this->dispatchEvent(FileUploadEvent::class, $language);

            $this->languageRepository->persistAndFlush($language);

            $this->session->getFlashBag()->add(
                'notice',
                sprintf('Language created successfully')
            );

            return new RedirectResponse($this->router->generate('admin_language_create'));
        }

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Languages',
            'form' => $form->createView(),
            'template' => '/Language/create.html.twig',
        ];

        return new Response(
            $this->templateWrapper->getTemplate($template, $data),
            400
        );
    }
    /**
     * @param Request $request
     * @param int $id
     * @param string|null $template
     * @param array|null $data
     * @return Response
     * @throws \Throwable
     */
    public function updateLanguage(Request $request, int $id, string $template = null, array $data = null) : Response
    {
        $language = $this->findLanguage($id);

        if (!$language instanceof Language) {
            throw new NotFoundHttpException();
        }

        $this->setImageIfExists($language);

        $form = $this->createForm($language);
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST' and $form->isSubmitted() and $form->isValid()) {
            $this->dispatchEvent(FileUploadEvent::class, $language);

            $this->languageRepository->persistAndFlush($language);

            $this->session->getFlashBag()->add(
                'notice',
                sprintf('Language updated successfully')
            );

            return new RedirectResponse($this->router->generate('admin_language_update', [
                'id' => $id
            ]));
        }

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'language' => $language,
            'listing_title' => 'Languages',
            'form' => $form->createView(),
            'template' => '/Language/update.html.twig',
        ];

        if ($form->isSubmitted() and !$form->isValid()) {
            return new Response(
                $this->templateWrapper->getTemplate($template, $data),
                400
            );
        }

        return new Response(
            $this->templateWrapper->getTemplate($template, $data),
            200
        );
    }
    /**
     * @param int $id
     * @return Language
     */
    public function findLanguage(int $id) : ?Language
    {
        /** @var Language $language */
        $language = $this->languageRepository->find($id);

        return $language;
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
    /**
     * @param Language $language
     */
    private function setImageIfExists(Language $language)
    {
        $image = $this->imageRepository->findBy(array(
            'language' => $language,
        ));

        if (!empty($image)) {
            $language->setImage($image[0]);
        }
    }
}