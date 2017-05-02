<?php

namespace API\SharedDataBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use API\SharedDataBundle\Controller\MasterController as SharedDataMasterController;

class MasterSecurityController extends SharedDataMasterController
{
    protected function createAccessDeniedJsonResponse(string $redirectUrl = null)
    {
        if (is_null($redirectUrl)) {
            $redirectUrl = $this->getParameter('security_admin_redirect');
        }

        $this->get('security.token_storage')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        $response = new JsonResponse(array(
            'redirect_url' => $redirectUrl,
        ));

        $response->setStatusCode(403);

        return $response;
    }
}