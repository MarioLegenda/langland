<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Category;
use Symfony\Component\HttpFoundation\JsonResponse;
use ArmorBundle\Admin\AdminAuthInterface;
use API\SharedDataBundle\Controller\CategoryController as SharedDataController;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends SharedDataController implements AdminAuthInterface
{
    public function createAction(Request $request)
    {
        $category = Category::createFromRequest($request->request);
        $response = $this->get('app.manual_validator')->validate($category);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        return parent::createAction($request);
    }

    public function indexAction()
    {
        
    }

    public function findAllAction(Request $request)
    {
        return parent::findAllAction($request);
    }
}
