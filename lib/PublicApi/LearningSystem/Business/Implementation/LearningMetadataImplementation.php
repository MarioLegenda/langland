<?php

namespace PublicApi\LearningSystem\Business\Implementation;

use PublicApi\LearningSystem\Repository\LearningMetadataRepository;

class LearningMetadataImplementation
{
    /**
     * @var LearningMetadataRepository $learningMetadataRepository
     */
    private $learningMetadataRepository;
    /**
     * LearningMetadataImplementation constructor.
     * @param LearningMetadataRepository $learningMetadataRepository
     */
    public function __construct(
        LearningMetadataRepository $learningMetadataRepository
    ) {
        $this->learningMetadataRepository = $learningMetadataRepository;
    }
}