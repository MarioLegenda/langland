<?php

namespace AdminBundle\Controller;

use ArmorBundle\Admin\AdminAuthInterface;
use AdminBundle\Entity\Word;
use Symfony\Component\HttpFoundation\JsonResponse;
use API\SharedDataBundle\Controller\WordController as SharedDataController;
use Symfony\Component\HttpFoundation\Request;

class WordController extends SharedDataController implements AdminAuthInterface
{
    public function createAction(Request $request)
    {
        $word = Word::createFromRequest($request);

        $response = $this->get('app.manual_validator')->validate($word);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        return parent::createAction($request);
    }

    public function scheduleRemovalAction(Request $request)
    {
        return $this->forward('SharedDataBundle:Word:scheduleRemoval');
    }

    public function removeAction(Request $request)
    {
        return $this->forward('SharedDataBundle:Word:remove');
    }
}
