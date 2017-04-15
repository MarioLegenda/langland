<?php

namespace API\SharedDataBundle\Controller;

use API\SharedDataBundle\Entity\Category;
use API\SharedDataBundle\Repository\Status;
use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Controller\MasterSecurityController;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends MasterSecurityController implements AdminAuthInterface
{
    public function createAction(Request $request)
    {
        $categoryRepo = $this->get('api.shared.category_repository');

        $data = $request->request->all();

        $resultResolver = $categoryRepo->findCategory($data);

        if ($resultResolver->getStatus() === Status::SUCCESS) {
            return $this->createFailedJsonResponse(array(sprintf('Category \'%s\' already exists', $data['category'])));
        }

        $resultResolver = $categoryRepo->create($data);

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createInternalErrorJsonResponse(array(
                sprintf('Category %s could not be created', $data['category'])
            ));
        }

        return $this->createSuccessJsonResponse();
    }

    public function findAllAction(Request $request)
    {
        $resultResolver = $this->get('api.shared.category_repository')->findAll($request->request->all());

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createSuccessJsonResponse();
        }

        $data = $resultResolver->getData();
        if (is_string(array_keys($data)[0])) {
            $data = array($data);
        }

        return $this->createSuccessJsonResponse(array(
            'categories' => $data,
        ));
    }
}
