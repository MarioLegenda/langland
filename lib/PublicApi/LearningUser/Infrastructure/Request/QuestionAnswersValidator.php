<?php

namespace PublicApi\LearningUser\Infrastructure\Request;

use LearningSystem\Infrastructure\Questions;

class QuestionAnswersValidator
{
    /**
     * @var QuestionAnswers $questionAnswers
     */
    private $questionAnswers;
    /**
     * @var Questions $questions
     */
    private $questions;
    /**
     * QuestionAnswersValidator constructor.
     * @param QuestionAnswers $questionAnswers
     * @param Questions $questions
     */
    public function __construct(
        QuestionAnswers $questionAnswers,
        Questions $questions
    ) {
        $this->questionAnswers = $questionAnswers;
        $this->questions = $questions;
    }
    /**
     * @throws \RuntimeException
     */
    public function validate()
    {
        $this->questions->areTypesValid($this->questionAnswers->getAnswers());

        foreach ($this->questionAnswers as $type => $questionAnswer) {
            $this->questions->checkValidAnswer($type, $questionAnswer, [
                'speaking_languages' => function(string $type, string $questionAnswer) {
                    if (!is_numeric($questionAnswer)) {
                        $message = sprintf('Answer for type \'%s\' has to be an integer', $type);
                        throw new \RuntimeException($message);
                    }
                }
            ]);
        }
    }
}