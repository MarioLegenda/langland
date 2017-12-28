<?php

namespace PublicApi\LearningUser\Business\Controller;

use PublicApi\LearningUser\Business\Implementation\LearningUserImplementation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class LearningUserController
{
    /**
     * @var LearningUserImplementation $learningUserImplementation
     */
    private $learningUserImplementation;
    /**
     * @var TokenStorage $tokenStorage
     */
    private $tokenStorage;
    /**
     * LearningUserController constructor.
     * @param LearningUserImplementation $learningUserImplementation
     * @param TokenStorage $tokenStorage
     */
    public function __construct(
        LearningUserImplementation $learningUserImplementation,
        TokenStorage $tokenStorage
    ) {
        $this->learningUserImplementation = $learningUserImplementation;
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @Security("has_role('ROLE_PUBLIC_API_USER')")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function registerLearningUser(Request $request): JsonResponse
    {
        $languageId = $request->request->get('languageId');

        if (empty($languageId)) {
            $message = sprintf('Language does not exist');
            throw new \RuntimeException($message);
        }

        $this->learningUserImplementation->registerLearningUser(
            $languageId,
            $this->tokenStorage->getToken()->getUser()
        );

        return new JsonResponse([], 201);
    }
}