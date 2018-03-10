<?php

namespace PublicApi\LearningUser\Business\Implementation;

use PublicApi\LearningUser\Repository\LearningMetadataRepository;
use PublicApiBundle\Entity\LearningUser;

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
    /**
     * @param LearningUser $learningUser
     */
    public function createFirstLearningMetadata(LearningUser $learningUser)
    {

    }
}