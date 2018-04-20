<?php

namespace PublicApi\LearningSystem\Business\Controller;

use PublicApi\LearningSystem\Business\Implementation\LearningMetadataImplementation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LearningMetadataController
{
    /**
     * @var LearningMetadataImplementation $learningMetadataImplementation
     */
    private $learningMetadataImplementation;
    /**
     * LearningMetadataController constructor.
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
    public function getLearningLessonPresentation(): Response
    {
        return new JsonResponse(
            $this->learningMetadataImplementation->getLearningLessonPresentation(),
            200
        );
    }
    /**
     * @return Response
     */
    public function getLearningGamesPresentation(): Response
    {
        return new JsonResponse(
            $this->learningMetadataImplementation->getLearningGamesPresentation(),
            200
        );
    }
    /**
     * @param int $id
     * @return Response
     */
    public function getLearningLessonById(int $id): Response
    {
        return new JsonResponse(
            $this->learningMetadataImplementation->getLearningLessonById($id),
            200
        );
    }
}