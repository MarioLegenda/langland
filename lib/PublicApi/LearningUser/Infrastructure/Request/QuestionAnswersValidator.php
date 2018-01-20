<?php

namespace PublicApi\LearningUser\Infrastructure\Request;

class QuestionAnswersValidator
{
    /**
     * @var QuestionAnswers $questionAnswers
     */
    private $questionAnswers;
    /**
     * QuestionAnswersValidator constructor.
     * @param QuestionAnswers $questionAnswers
     */
    public function __construct(QuestionAnswers $questionAnswers)
    {
        $this->questionAnswers = $questionAnswers;
    }

    public function validate()
    {

    }
}