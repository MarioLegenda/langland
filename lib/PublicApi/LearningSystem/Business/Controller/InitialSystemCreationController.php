<?php

namespace PublicApi\LearningSystem\Business\Controller;

use ArmorBundle\Entity\User;
use PublicApi\LearningSystem\Business\Implementation\InitialSystemCreationImplementation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InitialSystemCreationController
{
    /**
     * @var InitialSystemCreationImplementation $initialSystemCreationImplementation
     */
    private $initialSystemCreationImplementation;
    /**
     * InitialSystemCreationController constructor.
     * @param InitialSystemCreationImplementation $initialSystemCreationImplementation
     */
    public function __construct(
        InitialSystemCreationImplementation $initialSystemCreationImplementation
    ) {
        $this->initialSystemCreationImplementation = $initialSystemCreationImplementation;
    }
    /**
     * @param User $user
     * @return JsonResponse
     */
    public function createInitialDataAction(User $user): Response
    {
        return new JsonResponse(
            $this->initialSystemCreationImplementation->createInitialSystem($user->getCurrentLearningUser()),
            201
        );
    }
}