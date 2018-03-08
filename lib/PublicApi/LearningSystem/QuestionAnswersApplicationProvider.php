<?php

namespace PublicApi\LearningSystem;

use LearningSystem\Infrastructure\Questions;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswersValidator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class QuestionAnswersApplicationProvider
{
    /**
     * @var array $questionAnswers
     */
    private $questionAnswers;
    /**
     * QuestionAnswersApplicationResolver constructor.
     * @param TokenStorage $tokenStorage
     */
    public function __construct(
        TokenStorage $tokenStorage
    ) {
        $this->questionAnswers = $tokenStorage
            ->getToken()
            ->getUser()
            ->getCurrentLearningUser()
            ->getAnsweredQuestions();
    }
    /**
     * @return QuestionAnswers
     * @throws \RuntimeException
     */
    public function resolve(): QuestionAnswers
    {
        return $this->tryResolveQuestionAnswers();
    }
    /**
     * @return QuestionAnswers
     * @throws \RuntimeException
     */
    private function tryResolveQuestionAnswers(): QuestionAnswers
    {
        $questionAnswers = new QuestionAnswers($this->questionAnswers);

        $validator = new QuestionAnswersValidator($questionAnswers, new Questions());

        $validator->validate();

        return $questionAnswers;
    }
}