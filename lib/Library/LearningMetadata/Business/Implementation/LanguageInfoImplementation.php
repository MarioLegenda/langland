<?php

namespace Library\LearningMetadata\Business\Implementation;

use Library\Infrastructure\Form\FormBuilderInterface;
use Library\LearningMetadata\Infrastructure\Form\Type\LanguageInfoType;
use Library\LearningMetadata\Presentation\Template\TemplateWrapper;
use Library\LearningMetadata\Repository\Implementation\LanguageInfoRepository;
use Library\LearningMetadata\Repository\Implementation\LanguageInfoTextRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Router;
use Library\LearningMetadata\Repository\Implementation\ImageRepository;
use AdminBundle\Entity\LanguageInfo;
use Library\Event\EntityProcessorEvent;

class LanguageInfoImplementation
{
    /**
     * @var TemplateWrapper $templateWrapper
     */
    private $templateWrapper;
    /**
     * @var LanguageInfoRepository $languageInfoRepository
     */
    private $languageInfoRepository;
    /**
     * @var LanguageInfoTextRepository $languageInfoTextRepository
     */
    private $languageInfoTextRepository;
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
     * LanguageInfoImplementation constructor.
     * @param LanguageInfoRepository $languageInfoRepository
     * @param LanguageInfoTextRepository $languageInfoTextRepository
     * @param ImageRepository $imageRepository
     * @param FormBuilderInterface $formBuilder
     * @param TemplateWrapper $templateWrapper
     * @param Router $router
     * @param Session $session
     */
    public function __construct(
        LanguageInfoRepository $languageInfoRepository,
        LanguageInfoTextRepository $languageInfoTextRepository,
        ImageRepository $imageRepository,
        FormBuilderInterface $formBuilder,
        TemplateWrapper $templateWrapper,
        Router $router,
        TraceableEventDispatcher $eventDispatcher,
        Session $session
    ) {
        $this->languageInfoRepository = $languageInfoRepository;
        $this->languageInfoTextRepository = $languageInfoTextRepository;
        $this->imageRepository = $imageRepository;
        $this->formBuilder = $formBuilder;
        $this->templateWrapper = $templateWrapper;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
    }
    /**
     * @return LanguageInfo[]
     */
    public function getWords() : array
    {
        return $this->languageInfoRepository->findAll();
    }
    /**
     * @param array|LanguageInfo|null $data
     * @param array $options
     * @return FormInterface
     */
    public function createForm($data = null, array $options = array()) : FormInterface
    {
        return $this->formBuilder->getForm(LanguageInfoType::class, $data);
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
            'listing_title' => 'Language infos',
            'languageInfos' => $this->getWords(),
            'template' => '/LanguageInfo/index.html.twig',
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
        $form = $this->createForm(new LanguageInfo());

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Language infos',
            'form' => $form->createView(),
            'template' => '/LanguageInfo/create.html.twig',
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
    public function newLanguageInfo(Request $request, string $template = null, array $data = null) : Response
    {
        $languageInfo = new LanguageInfo();
        $form = $this->createForm($languageInfo);
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST' and $form->isSubmitted() and $form->isValid()) {
            try {

                $this->dispatchEvent(EntityProcessorEvent::class, array(
                    'languageInfo' => $languageInfo,
                ));

                $this->languageInfoRepository->persistAndFlush($languageInfo);
            } catch (\Throwable $e) {
                // log exception here
                throw $e;
            }

            $this->session->getFlashBag()->add(
                'notice',
                sprintf('Language info created successfully')
            );

            return new RedirectResponse($this->router->generate('admin_language_info_create'));
        }

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Language infos',
            'form' => $form->createView(),
            'template' => '/LanguageInfo/create.html.twig',
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
    public function updateLanguageInfo(Request $request, int $id, string $template = null, array $data = null) : Response
    {
        $languageInfo = $this->findLanguageInfo($id);

        if (!$languageInfo instanceof LanguageInfo) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm($languageInfo);
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST' and $form->isSubmitted() and $form->isValid()) {
            $this->dispatchEvent(EntityProcessorEvent::class, [
                'languageInfo' => $languageInfo,
            ]);

            $this->removeDeletetedTexts($languageInfo);

            $this->languageInfoRepository->persistAndFlush($languageInfo);

            $this->session->getFlashBag()->add(
                'notice',
                sprintf('Language info updated successfully')
            );

            return new RedirectResponse($this->router->generate('admin_language_info_update', [
                'id' => $id
            ]));
        }

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'languageInfo' => $languageInfo,
            'listing_title' => 'Language infos',
            'form' => $form->createView(),
            'template' => '/LanguageInfo/update.html.twig',
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
     * @return LanguageInfo|null
     */
    public function findLanguageInfo(int $id) : ?LanguageInfo
    {
        /** @var LanguageInfo $languageInfo */
        $languageInfo = $this->languageInfoRepository->find($id);

        return $languageInfo;
    }
    /**
     * @param string $eventClass
     * @param $entities
     * @return void
     */
    protected function dispatchEvent(string $eventClass, $entities)
    {
        $event = new $eventClass($entities);

        $this->eventDispatcher->dispatch($event::NAME, $event);
    }
    /**
     * @param int $id
     * @return RedirectResponse
     * @throws NotFoundHttpException
     */
    public function removeLanguageInfo(int $id)
    {
        /** @var LanguageInfo $languageInfo */
        $languageInfo = $this->languageInfoRepository->find($id);

        if (!$languageInfo instanceof LanguageInfo) {
            throw new NotFoundHttpException();
        }

        $this->languageInfoRepository->removeAndFlush($languageInfo);

        return new RedirectResponse($this->router->generate('admin_language_info_index'));
    }
    /**
     * @param LanguageInfo $languageInfo
     */
    private function removeDeletetedTexts(LanguageInfo $languageInfo)
    {
        $dbLanguageInfoTexts = $this->languageInfoTextRepository->findBy(array(
            'languageInfo' => $languageInfo,
        ));

        foreach ($dbLanguageInfoTexts as $text) {
            if (!$languageInfo->hasLanguageInfoText($text)) {
                $this->languageInfoTextRepository->removeAndFlush($text);
            }
        }
    }
}