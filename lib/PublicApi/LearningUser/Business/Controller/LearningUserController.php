<?php

namespace PublicApi\LearningUser\Business\Controller;

use AdminBundle\Entity\Language;
use ApiSDK\ApiSDK;
use ArmorBundle\Entity\User;
use PublicApi\LearningUser\Business\Implementation\LearningMetadataImplementation;
use PublicApi\LearningUser\Business\Implementation\LearningUserImplementation;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
use PublicApiBundle\Entity\LearningUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
     * @param User $user
     * @return Response
     */
    public function markLanguageInfoLooked(User $user): Response
    {
        return new JsonResponse(
            $this->learningUserImplementation->markLanguageInfoLooked($user->getCurrentLearningUser()),
            201
        );
    }
    /**
     * @Security("has_role('ROLE_PUBLIC_API_USER')")
     *
     * @param User $user
     * @return Response
     */
    public function isLanguageInfoLooked(User $user): Response
    {
        return new JsonResponse(
            $this->learningUserImplementation->getIsLanguageInfoLooked($user->getCurrentLearningUser()),
            200
        );
    }
    /**
     * @Security("has_role('ROLE_PUBLIC_API_USER')")
     *
     * @param User $user
     * @return Response
     */
    public function getCurrentLearningUser(User $user): Response
    {
        return new JsonResponse(
            $this->learningUserImplementation->getCurrentLearningUser($user),
            200
        );
    }
    /**
     * @Security("has_role('ROLE_PUBLIC_API_USER')")
     *
     * @param User $user
     * @return Response
     */
    public function getDynamicComponentsStatus(User $user): Response
    {
        return new JsonResponse(
            $this->learningUserImplementation->getDynamicComponentsStatus($user),
            200
        );
    }
    /**
     * @Security("has_role('ROLE_PUBLIC_API_USER')")
     *
     * @return Response
     */
    public function getQuestions(): Response
    {
        return new JsonResponse(
            $this->learningUserImplementation->getQuestions(),
            200
        );
    }
    /**
     * @Security("has_role('ROLE_PUBLIC_API_USER')")
     *
     * @param QuestionAnswers $questionAnswers
     * @return Response
     */
    public function validateQuestions(QuestionAnswers $questionAnswers): Response
    {
        $response = $this->learningUserImplementation->validateQuestionAnswers($questionAnswers);

        return new JsonResponse(
            $response,
            $response['statusCode']
        );
    }
    /**
     * @Security("has_role('ROLE_PUBLIC_API_USER')")
     *
     * @param User $user
     * @param QuestionAnswers $questionAnswers
     * @return Response
     */
    public function markQuestionsAnswered(User $user, QuestionAnswers $questionAnswers): Response
    {
        /** @var ApiSDK $response */
        $response = $this->learningUserImplementation->markQuestionsAnswered($user, $questionAnswers);

        return new JsonResponse(
            $response,
            $response['statusCode']
        );
    }
}