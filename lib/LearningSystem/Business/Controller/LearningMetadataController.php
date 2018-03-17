<?php

namespace LearningSystem\Business\Controller;

use LearningSystem\Business\Implementation\LearningMetadataImplementation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LearningMetadataController
{
    /**
     * @var LearningMetadataImplementation $learningMetadataImplementation
     */
    private $learningMetadataImplementation;

    /**
     * MetadataController constructor.
     * @param LearningMetadataImplementation $learningMetadataImplementation
     */
    public function __construct(
        LearningMetadataImplementation $learningMetadataImplementation
    ) {
        $this->learningMetadataImplementation = $learningMetadataImplementation;
    }
    /**
     * @return Response
     */
    public function getLearningMetadata(): Response
    {
        return new JsonResponse(
            $this->learningMetadataImplementation->getMetadataForLearningUser(),
            200
        );
    }
}