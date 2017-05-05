<?php

namespace API\ApiBundle\Controller;

use API\ApiBundle\Resolver\ResultResolver;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class JsonController extends Controller
{
    /**
     * @param ResultResolver $resultResolver
     * @return JsonResponse
     */
    public function createResponse(ResultResolver $resultResolver) : JsonResponse
    {
        $validity = $resultResolver->getValidity();

        if ($validity['valid'] === false) {
            return $this->createErrorResponse($validity['message']);
        }

        $result = $resultResolver->resolve()->getResult();

        if (empty($result)) {
            return $this->createFailureResponse();
        }

        return $this->createSuccessResponse($result);
    }
    /**
     * @param array $data
     * @return JsonResponse
     */
    private function createSuccessResponse(array $data) : JsonResponse
    {
        return new JsonResponse(array(
            'data' => array(
                'status' => 'success',
                'data' => $data,
            )
        ));
    }
    /**
     * @return JsonResponse
     */
    private function createFailureResponse() : JsonResponse
    {
        return new JsonResponse(array(
            'data' => array(
                'status' => 'failure',
                'message' => 'API did not find any results',
            )
        ));
    }
    /**
     * @param string $errorMessage
     * @return JsonResponse
     */
    private function createErrorResponse(string $errorMessage) : JsonResponse
    {
        return new JsonResponse(array(
            'data' => array(
                'status' => 'failure',
                'message' => $errorMessage,
            )
        ));
    }
}