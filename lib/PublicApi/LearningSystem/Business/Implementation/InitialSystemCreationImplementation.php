<?php

namespace PublicApi\LearningSystem\Business\Implementation;

use ApiSDK\ApiSDK;
use PublicApiBundle\Entity\LearningUser;

class InitialSystemCreationImplementation
{
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * InitialSystemCreationImplementation constructor.
     * @param ApiSDK $apiSDK
     */
    public function __construct(
        ApiSDK $apiSDK
    ) {
        $this->apiSdk = $apiSDK;
    }
    /**
     * @param LearningUser $user
     * @return array
     */
    public function createInitialSystem(LearningUser $user): array
    {

    }
}