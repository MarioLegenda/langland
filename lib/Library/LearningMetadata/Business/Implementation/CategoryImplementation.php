<?php

namespace Library\LearningMetadata\Business\Implementation;

use AdminBundle\Entity\Category;
use AdminBundle\Entity\Language;
use Library\Infrastructure\Form\FormBuilderInterface;
use Library\LearningMetadata\Infrastructure\Form\Type\CategoryType;
use Library\LearningMetadata\Presentation\Template\TemplateWrapper;
use Library\LearningMetadata\Repository\Implementation\CategoryRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Library\Event\FileUploadEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Router;

class CategoryImplementation
{
    /**
     * @var TemplateWrapper $templateWrapper
     */
    private $templateWrapper;
    /**
     * @var CategoryRepository $categoryRepository
     */
    private $categoryRepository;
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
     * CategoryImplementation constructor.
     * @param CategoryRepository $categoryRepository
     * @param FormBuilderInterface $formBuilder
     * @param TemplateWrapper $templateWrapper
     * @param Router $router
     * @param Session $session
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        FormBuilderInterface $formBuilder,
        TemplateWrapper $templateWrapper,
        Router $router,
        TraceableEventDispatcher $eventDispatcher,
        Session $session
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->formBuilder = $formBuilder;
        $this->templateWrapper = $templateWrapper;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->session = $session;
    }

    /**
     * @return Language[]
     */
    public function getCategories() : array
    {
        return $this->categoryRepository->findAll();
    }
    /**
     * @param array|Language|null $data
     * @param array $options
     * @return FormInterface
     */
    public function createForm($data = null, array $options = array()) : FormInterface
    {
        return $this->formBuilder->getForm(CategoryType::class, $data);
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
            'listing_title' => 'Categories',
            'categories' => $this->getCategories(),
            'template' => '/Category/index.html.twig',
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
        $form = $this->createForm(new Category());

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Categories',
            'form' => $form->createView(),
            'template' => '/Category/create.html.twig',
        ];

        return new Response($this->templateWrapper->getTemplate($template, $data), 200);
    }

    public function newCategory(Request $request, string $template = null, array $data = null) : Response
    {
        $category = new Category();
        $form = $this->createForm($category);
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST' and $form->isSubmitted() and $form->isValid()) {
            try {
                $this->dispatchEvent(FileUploadEvent::class, $category);

                $this->categoryRepository->persistAndFlush($category);
            } catch (\Throwable $e) {
                // log exception here
                throw $e;
            }

            $this->session->getFlashBag()->add(
                'notice',
                sprintf('Category created successfully')
            );

            return new RedirectResponse($this->router->generate('admin_category_create'));
        }

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'listing_title' => 'Categories',
            'form' => $form->createView(),
            'template' => '/Category/create.html.twig',
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
    public function updateCategory(Request $request, int $id, string $template = null, array $data = null) : Response
    {
        $category = $this->findCategory($id);

        if (!$category instanceof Category) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm($category);
        $form->handleRequest($request);

        if ($request->getMethod() === 'POST' and $form->isSubmitted() and $form->isValid()) {
            try {
                $this->dispatchEvent(FileUploadEvent::class, $category);

                $this->categoryRepository->persistAndFlush($category);
            } catch (\Throwable $e) {
                // log exception here
                throw $e;
            }

            $this->session->getFlashBag()->add(
                'notice',
                sprintf('Category updated successfully')
            );

            return new RedirectResponse($this->router->generate('admin_category_update', [
                'id' => $id
            ]));
        }

        $template = (is_string($template)) ? $template : '::Admin/Template/Panel/_action.html.twig';
        $data = (is_array($data)) ? $data : [
            'category' => $category,
            'listing_title' => 'Categories',
            'form' => $form->createView(),
            'template' => '/Category/update.html.twig',
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
     * @return Category
     */
    public function findCategory(int $id) : ?Category
    {
        /** @var Category $category */
        $category = $this->categoryRepository->find($id);

        return $category;
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