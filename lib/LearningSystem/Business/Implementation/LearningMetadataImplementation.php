<?php

namespace LearningSystem\Business\Implementation;

use ApiSDK\ApiSDK;
use LearningSystem\Business\Repository\LearningMetadataRepository;
use LearningSystem\Infrastructure\Provider\LearningUserProvider;

class LearningMetadataImplementation
{
    /**
     * @var LearningMetadataRepository $learningMetadataRepository
     */
    private $learningMetadataRepository;
    /**
     * @var LearningUserProvider $learningUserProvider
     */
    private $learningUserProvider;
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * MetadataImplementation constructor.
     * @param LearningUserProvider $learningUserProvider
     * @param ApiSDK $apiSDK
     * @param LearningMetadataRepository $learningMetadataRepository
     */
    public function __construct(
        LearningMetadataRepository $learningMetadataRepository,
        LearningUserProvider $learningUserProvider,
        ApiSDK $apiSDK
    ) {
        $this->learningMetadataRepository = $learningMetadataRepository;
        $this->learningUserProvider = $learningUserProvider;
        $this->apiSdk = $apiSDK;
    }
    /**
     * @return array
     */
    public function getMetadataForLearningUser(): array
    {
        $data = $this->learningMetadataRepository->getLearningMetadata($this->learningUserProvider->getLearningUser()->getId());

        return $this->apiSdk
            ->create($data)
            ->isCollection()
            ->method('GET')
            ->setStatusCode(200)
            ->build();
    }
}