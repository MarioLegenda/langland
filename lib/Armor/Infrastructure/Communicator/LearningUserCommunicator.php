<?php

namespace Armor\Infrastructure\Communicator;

use PublicApi\LearningUser\Repository\LearningUserRepository;

class LearningUserCommunicator
{
    /**
     * @var LearningUserRepository $learningUserRepository
     */
    private $learningUserRepository;
    /**
     * LearningUserCommunicator constructor.
     * @param LearningUserRepository $learningUserRepository
     */
    public function __construct(
        LearningUserRepository $learningUserRepository
    ) {
        $this->learningUserRepository = $learningUserRepository;
    }
}