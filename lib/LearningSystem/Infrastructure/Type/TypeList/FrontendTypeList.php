<?php

namespace LearningSystem\Infrastructure\Type\TypeList;

use LearningSystem\Infrastructure\Type\ChallengesType;
use LearningSystem\Infrastructure\Type\FreeTimeType;
use LearningSystem\Infrastructure\Type\LearningTimeType;
use LearningSystem\Infrastructure\Type\MemoryType;
use LearningSystem\Infrastructure\Type\PersonType;
use LearningSystem\Infrastructure\Type\ProfessionType;
use LearningSystem\Infrastructure\Type\SpeakingLanguagesType;
use LearningSystem\Infrastructure\Type\StressfulJobType;

class FrontendTypeList
{
    /**
     * @return array
     */
    public static function getList(): array
    {
        return [
            SpeakingLanguagesType::getName() => SpeakingLanguagesType::class,
            ProfessionType::getName() => ProfessionType::class,
            PersonType::getName() => PersonType::class,
            LearningTimeType::getName() => LearningTimeType::class,
            FreeTimeType::getName() => FreeTimeType::class,
            MemoryType::getName() => MemoryType::class,
            ChallengesType::getName() => ChallengesType::class,
            StressfulJobType::getName() => StressfulJobType::class,
        ];
    }
}