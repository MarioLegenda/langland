<?php

namespace PublicApi\LearningUser\Infrastructure\Request;

class QuestionAnswers
{
    /**
     * @var array $answers
     */
    private $answers;
    /**
     * QuestionAnswers constructor.
     * @param array $answers
     */
    public function __construct(array $answers)
    {
        $this->answers = $answers;
    }
}