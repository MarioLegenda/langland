<?php

namespace LearningSystem\Business\Implementation;

use ApiSDK\ApiSDK;
use LearningSystem\Infrastructure\Questions;
use LearningSystem\Infrastructure\Type\TypeList\FrontendTypeList;
use LearningSystem\Library\Converter\QuestionToTypeConverter;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswersValidator;
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
        $outcome = $this->validateQuestionAnswers($questionAnswers);

        if (is_array($outcome)) {
            return $outcome;
        }

        $converter = new QuestionToTypeConverter($this->getTypes(), $questionAnswers);

        $converted = $converter->getConverted();
    }
    /**
     * @param QuestionAnswers $questionAnswers
     * @return array|bool
     */
    private function validateQuestionAnswers(QuestionAnswers $questionAnswers)
    {
        $validator = new QuestionAnswersValidator($questionAnswers, new Questions());

        try {
            $validator->validate();
        } catch (\RuntimeException $e) {
            return $this->apiSdk
                ->create([])
                ->setStatusCode(403)
                ->isResource()
                ->method('POST')
                ->build();
        }

        return true;
    }
    /**
     * @return array
     */
    private function getTypes(): array
    {
        $answers = FrontendTypeList::getList();

        return $answers;
    }
}