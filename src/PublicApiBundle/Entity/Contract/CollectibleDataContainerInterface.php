<?php

namespace PublicApiBundle\Entity\Contract;

use PublicApiBundle\Entity\LearningMetadata;

interface CollectibleDataContainerInterface
{
    /**
     * @param LearningMetadata $learningMetadata
     */
    public function setLearningMetadata(LearningMetadata $learningMetadata): void;
    /**
     * @return LearningMetadata
     */
    public function getLearningMetadata(): LearningMetadata;
}