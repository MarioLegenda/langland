<?php

namespace PublicApi\Language\Business\Controller;

use PublicApi\Language\Business\Implementation\LanguageImplementation;
use Symfony\Component\HttpFoundation\JsonResponse;

class LanguageController
{
    /**
     * @var LanguageImplementation $languageImplementation
     */
    private $languageImplementation;

    public function __construct(
        LanguageImplementation $languageImplementation
    ) {
        $this->languageImplementation = $languageImplementation;
    }
    /**
     * @return JsonResponse
     * @throws \BlueDot\Exception\BlueDotRuntimeException
     * @throws \BlueDot\Exception\ConnectionException
     */
    public function getAll(): JsonResponse
    {
        $data = $this->languageImplementation->findAll();

        return new JsonResponse($data, 200);
    }
}