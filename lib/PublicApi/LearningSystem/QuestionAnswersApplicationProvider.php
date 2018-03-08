<?php

namespace PublicApi\LearningSystem;

use LearningSystem\Infrastructure\Questions;
use PublicApi\LearningUser\Infrastructure\Provider\LearningUserProvider;
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
     * @param LearningUserProvider $learningUserProvider
     */
    public function __construct(
        LearningUserProvider $learningUserProvider
    ) {
        $this->questionAnswers = $learningUserProvider
            ->getLearningUser()
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