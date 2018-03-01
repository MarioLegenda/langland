<?php

namespace LearningSystem\Business\Controller;

use ArmorBundle\Entity\User;
use LearningSystem\Business\Implementation\InitialSystemCreationImplementation;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
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
        $learningUser = $user->getCurrentLearningUser();
        $answeredQuestions = new QuestionAnswers($learningUser->getAnsweredQuestions());

        return new JsonResponse(
            $this->initialSystemCreationImplementation->createInitialSystem(
                $user->getCurrentLearningUser(),
                $answeredQuestions
            ),
            201
        );
    }
}