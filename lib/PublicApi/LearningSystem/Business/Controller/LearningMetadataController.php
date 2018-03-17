<?php

namespace PublicApi\LearningSystem\Business\Controller;

use PublicApi\LearningUser\Business\Implementation\LearningMetadataImplementation;

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
}