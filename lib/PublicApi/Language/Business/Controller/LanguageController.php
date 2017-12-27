<?php

namespace PublicApi\Language\Business\Controller;

use PublicApi\Language\Business\Implementation\LanguageImplementation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LanguageController
{
    /**
     * @var LanguageImplementation $languageImplementation
     */
    private $languageImplementation;
    /**
     * LanguageController constructor.
     * @param LanguageImplementation $languageImplementation
     */
    public function __construct(
        LanguageImplementation $languageImplementation
    ) {
        $this->languageImplementation = $languageImplementation;
    }
    /**
     * @Security("has_role('ROLE_PUBLIC_API_USER')")
     *
     * @return JsonResponse
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function getAll(): JsonResponse
    {
        $data = $this->languageImplementation->findAll();

        return new JsonResponse($data, 200);
    }
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {

    }
}