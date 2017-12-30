<?php

namespace PublicApi\LearningUser\Business\Controller;

use AdminBundle\Entity\Language;
use ArmorBundle\Entity\User;
use PublicApi\LearningUser\Business\Implementation\LearningUserImplementation;
use PublicApiBundle\Entity\LearningUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class LearningUserController
{
    /**
     * @var LearningUserImplementation $learningUserImplementation
     */
    private $learningUserImplementation;
    /**
     * LearningUserController constructor.
     * @param LearningUserImplementation $learningUserImplementation
     */
    public function __construct(
        LearningUserImplementation $learningUserImplementation
    ) {
        $this->learningUserImplementation = $learningUserImplementation;
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
            $this->learningUserImplementation->updateLearningUser(
                $learningUser,
                $user
            );

            return new JsonResponse('Resource already exists', 204);
        }

        $this->learningUserImplementation->registerLearningUser(
            $language,
            $user
        );

        return new JsonResponse([], 201);
    }
}