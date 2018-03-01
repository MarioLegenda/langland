<?php

namespace LearningSystem\Unit;

use LearningSystem\Infrastructure\Type\TypeList\FrontendTypeList;
use LearningSystem\Library\Converter\QuestionToTypeConverter;
use PHPUnit\Framework\TestCase;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
use LearningSystem\Infrastructure\Type\ChallengesType;
use LearningSystem\Infrastructure\Type\FreeTimeType;
use LearningSystem\Infrastructure\Type\LearningTimeType;
use LearningSystem\Infrastructure\Type\MemoryType;
use LearningSystem\Infrastructure\Type\PersonType;
use LearningSystem\Infrastructure\Type\ProfessionType;
use LearningSystem\Infrastructure\Type\SpeakingLanguagesType;
use LearningSystem\Infrastructure\Type\StressfulJobType;

class QuestionToTypeConverterTest extends TestCase
{
    public function test_question_to_type_converter()
    {
       $answers = [
            SpeakingLanguagesType::getName() => 2,
            ProfessionType::getName() => 'arts_and_entertainment',
            PersonType::getName() => 'risk_taker',
            LearningTimeType::getName() => 'morning',
            FreeTimeType::getName() => '30_minutes',
            MemoryType::getName() => 'short_term',
            ChallengesType::getName() => 'likes_challenges',
            StressfulJobType::getName() => 'stressful_job'
        ];

        $converter = new QuestionToTypeConverter(FrontendTypeList::getList(), new QuestionAnswers($answers));

        $converted = $converter->getConverted();

        foreach (FrontendTypeList::getList() as $typeName => $typeClass) {
            static::assertArrayHasKey($typeName, $converted);
            static::assertInstanceOf($typeClass, $converted[$typeName]);
        }
    }
}