<?php

namespace AdminBundle\Controller\Ajax;

use AdminBundle\Controller\RepositoryController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WordController extends RepositoryController
{
    public function autocompleteSearchAction($search)
    {
        return new JsonResponse(array(
            'data' => $this->getRepository('AdminBundle:Word')->findWordsByPattern($search),
        ));
    }

    public function findTagWordsByIdsAction(Request $request, $ids)
    {
        $ids = explode(',', $ids);

        $words = $this->getRepository('AdminBundle:Word')->findMultipleById($ids);

        $data = array();
        foreach ($words as $word) {
            $temp['id'] = $word->getId();
            $temp['value'] = $word->getName();

            $data[] = $temp;
        }

        return new JsonResponse(array(
            'data' => $data,
        ));
    }
}