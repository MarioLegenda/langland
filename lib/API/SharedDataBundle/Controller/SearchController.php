<?php

namespace API\SharedDataBundle\Controller;

use API\SharedDataBundle\Repository\Status;
use ArmorBundle\Admin\AdminAuthInterface;
use ArmorBundle\Controller\MasterSecurityController;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends MasterSecurityController implements AdminAuthInterface
{
    public function searchAction(Request $request)
    {
        $resultResolver = $this->get('api.shared.search_repository')->searchWords($request->request->all());

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse(array('Something went wrong. Please, refresh the page and try again'));
        }

        return $this->createSuccessJsonResponse(array(
            'words' => $resultResolver->getData(),
        ));
    }

    public function findLastWordsAction(Request $request)
    {
        $resultResolver = $this->get('api.shared.search_repository')->findLastWords($request->request->all());

        if ($resultResolver->getStatus() === Status::FAILURE) {
            return $this->createFailedJsonResponse();
        }

        return $this->createSuccessJsonResponse(array(
            'words' => $resultResolver->getData(),
        ));
    }
}
