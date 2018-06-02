<?php

namespace PublicApi\LearningSystem\Business\Controller;

use PublicApi\LearningSystem\Business\Implementation\InitialDataCreationImplementation;
use PublicApi\LearningSystem\Business\Implementation\LearningMetadataImplementation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InitialDataCreationController
{
    /**
     * @var InitialDataCreationImplementation $initialDataCreationImplementation
     */
    private $initialDataCreationImplementation;
    /**
     * @var LearningMetadataImplementation $learningMetadataImplementation
     */
    private $learningMetadataImplementation;
    /**
     * InitialDataCreationController constructor.
     * @param InitialDataCreationImplementation $initialDataCreationImplementation
     * @param LearningMetadataImplementation $learningMetadataImplementation
     */
    public function __construct(
        InitialDataCreationImplementation $initialDataCreationImplementation,
        LearningMetadataImplementation $learningMetadataImplementation
    ) {
        $this->initialDataCreationImplementation = $initialDataCreationImplementation;
        $this->learningMetadataImplementation = $learningMetadataImplementation;
    }
    /**
     * @return Response
     */
    public function makeInitialDataCreation(): Response
    {
        $learningLesson = $this->learningMetadataImplementation->createLearningLessons();

        return new JsonResponse(
            $this->initialDataCreationImplementation->createInitialData(
                $learningLesson
            ),
            201
        );
    }
}