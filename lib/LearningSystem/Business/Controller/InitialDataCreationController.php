<?php

namespace LearningSystem\Business\Controller;

use LearningSystem\Business\Implementation\InitialDataCreationImplementation;
use LearningSystem\Infrastructure\Provider\LearningUserProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InitialDataCreationController
{
    /**
     * @var LearningUserProvider $learningUserProvider
     */
    private $learningUserProvider;
    /**
     * @var InitialDataCreationImplementation $initialDataCreationImplementation
     */
    private $initialDataCreationImplementation;
    /**
     * InitialDataCreationController constructor.
     * @param LearningUserProvider $learningUserProvider
     * @param InitialDataCreationImplementation $initialDataCreationImplementation
     */
    public function __construct(
        LearningUserProvider $learningUserProvider,
        InitialDataCreationImplementation $initialDataCreationImplementation
    ) {
        $this->learningUserProvider = $learningUserProvider;
        $this->initialDataCreationImplementation = $initialDataCreationImplementation;
    }

    public function makeInitialDataCreation(): Response
    {
        return new JsonResponse(
            $this->initialDataCreationImplementation->createInitialData($this->learningUserProvider->getLearningUser()),
            201
        );
    }
}