<?php

namespace PublicApi\LearningUser\Business\Controller;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use PublicApi\LearningUser\Business\Implementation\LearningUserImplementation;
use PublicApiBundle\Entity\LearningUser;
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
     * @param Language $language
     * @param User $user
     * @return JsonResponse
     */
    public function registerLearningUser(Language $language, User $user): JsonResponse
    {
        $learningUser = $this->learningUserImplementation->findExact($language, $user);

        if ($learningUser instanceof LearningUser) {
            return new JsonResponse([], 205);
        }

        $this->learningUserImplementation->registerLearningUser(
            $language,
            $user
        );

        return new JsonResponse([], 201);
    }
}