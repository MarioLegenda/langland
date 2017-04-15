<?php

namespace API\SharedDataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class MasterController extends Controller
{
    protected function createInternalErrorJsonResponse(array $messages = null) : JsonResponse
    {
        $response = new JsonResponse(array(
            'status' => 'failure',
            'errors' => ($messages === null) ? array() : $messages,
        ));

        $response->setStatusCode(500);

        return $response;
    }

    protected function createErrorJsonResponse(array $messages) : JsonResponse
    {
        return new JsonResponse(array(
            'status' => 'failure',
            'errors' => $messages,
        ));
    }

    protected function createFailedJsonResponse(array $messages = null) : JsonResponse
    {
        return new JsonResponse(array(
            'status' => 'failure',
            'errors' => ($messages === null) ? array() : $messages,
        ));
    }

    protected function createSuccessJsonResponse(array $data = null) : JsonResponse
    {
        return new JsonResponse(array(
            'status' => 'success',
            'data' => ($data === null) ? array() : $data,
        ));
    }
}