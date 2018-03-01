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

        $converted = [];
        foreach ($questionAnswers->getAnswers() as $type => $answer) {
            if (!array_key_exists($type, $typeConstructor)) {
                $message = sprintf('Invalid type in type constructor. Type %s given', $type);
                throw new \RuntimeException($message);
            }

            $typeClass = $typeConstructor[$type];
            $converted[$type] = $typeClass::fromValue($answer);
        }

        $this->converted = $converted;
    }
    /**
     * @return array
     */
    public function getConverted(): array
    {
        return $this->converted;
    }
}