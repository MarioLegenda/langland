<?php

namespace LearningSystem\Business\Implementation;

use ApiSDK\ApiSDK;
use LearningSystem\Infrastructure\Questions;
use LearningSystem\Infrastructure\Type\GameType\BasicGameType;
use LearningSystem\Infrastructure\Type\TypeList\FrontendTypeList;
use LearningSystem\Library\Converter\QuestionToTypeConverter;
use LearningSystem\Library\Rule\RuleFactory;
use PublicApi\LearningSystem\RuleResolver;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswersValidator;
use PublicApi\LearningUser\Repository\LearningUserRepository;
use PublicApiBundle\Entity\LearningUser;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;

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
     * @var RuleResolver $ruleResolver
     */
    private $ruleResolver;
    /**
     * InitialSystemCreationImplementation constructor.
     * @param ApiSDK $apiSDK
     * @param LearningUserRepository $learningUserRepository
     * @param RuleResolver $ruleResolver
     */
    public function __construct(
        LearningUserRepository $learningUserRepository,
        ApiSDK $apiSDK,
        RuleResolver $ruleResolver
    ) {
        $this->apiSdk = $apiSDK;
        $this->learningUserRepository = $learningUserRepository;
        $this->ruleResolver = $ruleResolver;
    }
    /**
     * @param LearningUser $learningUser
     * @return array
     */
    public function createInitialSystem(
        LearningUser $learningUser
    ): array {
        return [];
    }
}