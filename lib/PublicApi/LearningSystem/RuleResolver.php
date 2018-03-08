<?php

namespace PublicApi\LearningSystem;

use LearningSystem\Infrastructure\Type\GameType\BasicGameType;
use LearningSystem\Infrastructure\Type\TypeList\FrontendTypeList;
use LearningSystem\Library\Converter\QuestionToTypeConverter;
use LearningSystem\Library\Rule\RuleDataInterface;
use LearningSystem\Library\Rule\RuleFactory;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;

class RuleResolver
{
    /**
     * @var QuestionAnswersApplicationProvider $questionAnswersApplicationResolver
     */
    private $questionAnswersApplicationResolver;
    /**
     * RuleResolver constructor.
     * @param QuestionAnswersApplicationProvider $questionAnswersApplicationResolver
     */
    public function __construct(
        QuestionAnswersApplicationProvider $questionAnswersApplicationResolver
    ) {
        $this->questionAnswersApplicationResolver = $questionAnswersApplicationResolver;
    }
    /**
     * @return RuleDataInterface
     */
    public function resolve(): RuleDataInterface
    {
        $questionAnswers = $this->questionAnswersApplicationResolver->resolve();

        return RuleFactory::create(
                BasicGameType::getName(),
                $this->convertAsTypes($questionAnswers)
        );
    }
    /**
     * @param QuestionAnswers $questionAnswers
     * @return array
     */
    private function convertAsTypes(QuestionAnswers $questionAnswers): array
    {
        $converter = new QuestionToTypeConverter(FrontendTypeList::getList(), $questionAnswers);

        return $converter->convert();
    }
}