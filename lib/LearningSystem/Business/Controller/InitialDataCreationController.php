<?php

namespace LearningSystem\Business\Controller;

use LearningSystem\Business\Implementation\InitialDataCreationImplementation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InitialDataCreationController
{
    /**
     * @var InitialDataCreationImplementation $initialDataCreationImplementation
     */
    private $initialDataCreationImplementation;
    /**
     * InitialDataCreationController constructor.
     * @param InitialDataCreationImplementation $initialDataCreationImplementation
     */
    public function __construct(
        InitialDataCreationImplementation $initialDataCreationImplementation
    ) {
        $this->initialDataCreationImplementation = $initialDataCreationImplementation;
    }
    /**
     * @return Response
     */
    public function makeInitialDataCreation(): Response
    {
        return new JsonResponse(
            $this->initialDataCreationImplementation->createInitialData(),
            201
        );
    }
}