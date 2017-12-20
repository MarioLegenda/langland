<?php

namespace LearningMetadata\Business\Implementation;

use AdminBundle\Entity\Word;
use AdminBundle\Event\ImageUploadEvent;
use Library\Event\EntityProcessorEvent;
use Library\Infrastructure\Form\FormBuilderInterface;
use LearningMetadata\Infrastructure\Form\Type\WordType;
use Library\Presentation\Template\TemplateWrapper;
use LearningMetadata\Repository\Implementation\ImageRepository;
use LearningMetadata\Repository\Implementation\WordRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Router;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class WordImplementation
{
    /**
     * @var TemplateWrapper $templateWrapper
     */
    private $templateWrapper;
    /**
     * @var WordRepository $wordRepository
     */
    private $wordRepository;
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
     * WordImplementation constructor.
     * @param WordRepository $wordRepository
     * @param ImageRepository $imageRepository
     * @param FormBuilderInterface $formBuilder
     * @param TemplateWrapper $templateWrapper
     * @param Router $router
     * @param Session $session
     */
    public function __construct(
        WordRepository $wordRepository,
        ImageRepository $imageRepository,
        FormBuilderInterface $formBuilder,
        TemplateWrapper $templateWrapper,
        Router $router,
        TraceableEventDispatcher $eventDispatcher,
        Session $session
    ) {
        $this->wordRepository = $wordRepository;
        $this->imageRepository = $imageRepository;
        $this->formBuilder = $formBuilder;
        $this->templateWrapper = $templateWrapper;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
    }
    /**
     * @return Word[]
     */
    public function getWords() : array
    {
        return $this->wordRepository->findAll();
    }
    /**
     * @param array|Word|null $data
     * @param array $options
     * @return FormInterface
     */
    public function createForm($data = null, array $options = array()) : FormInterface
    {
        return $this->formBuilder->getForm(WordType::class, $data);
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
            'listing_title' => 'Words',
            'words' => $this->getWords(),
            'template' => '/Word/index.html.twig',
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
        $form = $this->createForm(new Word());

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Words',
            'form' => $form->createView(),
            'template' => '/Word/create.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }

    public function newWord(Request $request, string $template = null, array $data = null) : Response
    {
        $word = new Word();
        $form = $this->createForm($word);
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST' and $form->isSubmitted() and $form->isValid()) {
            $this->dispatchEvent(EntityProcessorEvent::class, [
                'word' => $word,
            ]);

            $this->dispatchEvent(ImageUploadEvent::class, $word);

            $this->wordRepository->persistAndFlush($word);

            $this->session->getFlashBag()->add(
                'notice',
                sprintf('Word created successfully')
            );

            return new RedirectResponse($this->router->generate('admin_word_create'));
        }

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Words',
            'form' => $form->createView(),
            'template' => '/Word/create.html.twig',
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
    public function updateWord(Request $request, int $id, string $template = null, array $data = null) : Response
    {
        $word = $this->findWord($id);

        if (!$word instanceof Word) {
            throw new NotFoundHttpException();
        }

        $this->setImageIfExists($word);

        $form = $this->createForm($word);
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST' and $form->isSubmitted() and $form->isValid()) {
            $this->dispatchEvent(EntityProcessorEvent::class, [
                'word' => $word,
            ]);

            $this->dispatchEvent(ImageUploadEvent::class, $word);

            $this->wordRepository->persistAndFlush($word);

            $this->session->getFlashBag()->add(
                'notice',
                sprintf('Word updated successfully')
            );

            return new RedirectResponse($this->router->generate('admin_word_update', [
                'id' => $id
            ]));
        }

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'word' => $word,
            'listing_title' => 'Words',
            'form' => $form->createView(),
            'template' => '/Word/update.html.twig',
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
     * @return Word
     */
    public function findWord(int $id) : ?Word
    {
        /** @var Word $word */
        $word = $this->wordRepository->find($id);

        return $word;
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
     * @param Word $word
     */
    private function setImageIfExists(Word $word)
    {
        $image = $this->imageRepository->findBy(array(
            'word' => $word,
        ));

        if (!empty($image)) {
            $word->setImage($image[0]);
        }
    }
}