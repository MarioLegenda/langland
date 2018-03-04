<?php

namespace LearningSystem\Library\Converter;

use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;

class QuestionToTypeConverter
{
    /**
     * @var array $typeConstructor
     */
    private $typeConstructor;
    /**
     * @var QuestionAnswers $questionAnswers
     */
    private $questionAnswers;
    /**
     * @var array $converted
     */
    private $converted;
    /**
     * QuestionToTypeConverter constructor.
     * @param array $typeConstructor
     * @param QuestionAnswers $questionAnswers
     */
    public function __construct(array $typeConstructor, QuestionAnswers $questionAnswers)
    {
        $this->typeConstructor = $typeConstructor;
        $this->questionAnswers = $questionAnswers;
    }
    /**
     * @return array
     */
    public function convert(): array
    {
        if (is_array($this->converted) and !empty($this->converted)) {
            return $this->converted;
        }

        $converted = [];
        foreach ($this->questionAnswers->getAnswers() as $type => $answer) {
            if (!array_key_exists($type, $this->typeConstructor)) {
                $message = sprintf('Invalid type in type constructor. Type %s given', $type);
                throw new \RuntimeException($message);
            }

            $typeClass = $this->typeConstructor[$type];
            $converted[$type] = $typeClass::fromValue($answer);
        }

        $this->converted = $converted;

        return $this->converted;
    }
}