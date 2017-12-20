<?php

namespace LearningMetadata\Business\Controller;

use LearningMetadata\Business\Implementation\CategoryImplementation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class CategoryController
 * @package Library\LearningMetadata\Business\Controller
 */
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
     * @Security("has_role('ROLE_ALLOWED_VIEW')")
     *
     * @return Response
     */
    public function indexAction() : Response
    {
        return $this->categoryImplementation->getListPresentation();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @return Response
     */
    public function createAction()
    {
        return $this->categoryImplementation->getCreatePresentation();
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param Request $request
     * @return Response
     */
    public function newAction(Request $request)
    {
        return $this->categoryImplementation->newCategory($request);
    }
    /**
     * @Security("has_role('ROLE_ALLOWED_MODIFY')")
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateAction(Request $request, int $id)
    {
        return $this->categoryImplementation->updateCategory($request, $id);
    }
}