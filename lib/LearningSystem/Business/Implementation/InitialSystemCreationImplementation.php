<?php

namespace LearningSystem\Business\Implementation;

use ApiSDK\ApiSDK;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
use PublicApi\LearningUser\Repository\LearningUserRepository;
use PublicApiBundle\Entity\LearningUser;

class InitialSystemCreationImplementation
{
    /**
     * @var LearningUserRepository $learningUserRepository
     */
    private $learningUserRepository;
    /**
     * @var ApiSDK $apiSdk
     */
    private $apiSdk;
    /**
     * InitialSystemCreationImplementation constructor.
     * @param ApiSDK $apiSDK
     * @param LearningUserRepository $learningUserRepository
     */
    public function __construct(
        LearningUserRepository $learningUserRepository,
        ApiSDK $apiSDK
    ) {
        $this->apiSdk = $apiSDK;
        $this->learningUserRepository = $learningUserRepository;
    }
    /**
     * @param LearningUser $user
     * @param QuestionAnswers $questionAnswers
     * @return array
     */
    public function createInitialSystem(LearningUser $user, QuestionAnswers $questionAnswers): array
    {

    }
}