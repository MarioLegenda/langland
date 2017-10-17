<?php

namespace Library\LearningMetadata\Business\Controller;

use Library\LearningMetadata\Business\Implementation\CategoryImplementation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CategoryController
{
    /**
     * @var CategoryImplementation $languageImplementation
     */
    private $categoryImplementation;
    /**
     * LanguageController constructor.
     * @param CategoryImplementation $categoryImplementation
     */
    public function __construct(
        CategoryImplementation $categoryImplementation
    ) {
        $this->categoryImplementation = $categoryImplementation;
    }
    /**
     * @return Response
     */
    public function indexAction() : Response
    {
        return $this->categoryImplementation->getListPresentation();
    }
    /**
     * @return Response
     */
    public function createAction()
    {
        return $this->categoryImplementation->getCreatePresentation();
    }
    /**
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->categoryImplementation->newCategory($request);
    }
    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateAction(Request $request, int $id)
    {
        return $this->categoryImplementation->updateCategory($request, $id);
    }
}