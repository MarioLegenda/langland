<?php

namespace PublicApi\LearningSystem\Infrastructure\DataDecider;

use LearningSystem\Infrastructure\Type\ChallengesType;
use LearningSystem\Infrastructure\Type\FreeTimeType;
use LearningSystem\Infrastructure\Type\GameType\BasicGameType;
use LearningSystem\Infrastructure\Type\PersonType;
use LearningSystem\Infrastructure\Type\SpeakingLanguagesType;
use LearningSystem\Infrastructure\Type\StressfulJobType;
use LearningSystem\Library\DataProviderInterface;
use LearningSystem\Infrastructure\Type\TypeInterface;
use PublicApi\LearningSystem\QuestionAnswersApplicationProvider;
use PublicApi\LearningUser\Infrastructure\Request\QuestionAnswers;
use PublicApi\LearningSystem\Infrastructure\DataProvider\Word\ProvidedWordDataCollection;
use LearningSystem\Library\DataDeciderInterface;

class InitialDataDecider implements DataDeciderInterface
{
    /**
     * @var QuestionAnswersApplicationProvider $questionAnswersApplicationResolver
     */
    private $questionAnswersApplicationResolver;
    /**
     * @var DataProviderInterface $wordDataProvider
     */
    private $wordDataProvider;
    /**
     * InitialDataDecider constructor.
     * @param QuestionAnswersApplicationProvider $questionAnswersApplicationResolver
     * @param DataProviderInterface $wordDataProvider
     */
    public function __construct(
        QuestionAnswersApplicationProvider $questionAnswersApplicationResolver,
        DataProviderInterface $wordDataProvider
    ) {
        $this->questionAnswersApplicationResolver = $questionAnswersApplicationResolver;
        $this->wordDataProvider = $wordDataProvider;
    }
    /**
     * @inheritdoc
     */
    public function getData(int $learningMetadataId): array
    {
        $questionAnswers = $this->questionAnswersApplicationResolver->resolve();

        $wordNumber = $this->resolveWordNumber($questionAnswers);
        $wordLevel = 1;

        /** @var ProvidedWordDataCollection $dataCollection */
        $dataCollection = $this->wordDataProvider->getData($learningMetadataId, [
            'word_number' => $wordNumber,
            'word_level' => $wordLevel,
        ]);

        return [
            'game_type' => BasicGameType::fromValue(BasicGameType::getName()),
            'data' => $dataCollection,
        ];
    }
    /**
     * @param QuestionAnswers $questionAnswers
     * @return int
     */
    private function resolveWordNumber(QuestionAnswers $questionAnswers): int
    {
        $wordNumber = 20;

        foreach ($questionAnswers as $typeName => $answer) {
            $type = $questionAnswers->asType($typeName);

            $wordNumber = $this->resolveWordNumberForType($type, $wordNumber);
        }

        if ($wordNumber < 15) {
            $wordNumber = 15;
        }

        return $wordNumber;
    }
    /**
     * @param TypeInterface $type
     * @param int $wordNumber
     * @return int
     */
    private function resolveWordNumberForType(TypeInterface $type, int $wordNumber): int
    {
        if ($type instanceof SpeakingLanguagesType) {
            if ($type->getValue() >= 1) {
                return ($wordNumber += 3);
            }
        }

        if ($type instanceof PersonType) {
            if ($type->getValue() === 'risk_taker') {
                return ($wordNumber += 3);
            }

            if ($type->getValue() === 'sure_thing') {
                return ($wordNumber -= 2);
            }

            return $wordNumber;
        }

        if ($type instanceof FreeTimeType) {
            if ($type->getValue() === '1_hour') {
                return ($wordNumber += 3);
            }

            if ($type->getValue() === '2_hours') {
                return ($wordNumber += 3);
            }

            if ($type->getValue() === 'all_time') {
                return ($wordNumber += 5);
            }
        }

        if ($type instanceof ChallengesType) {
            if ($type->getValue() === 'likes_challenges') {
                return ($wordNumber += 2);
            }

            if ($type->getValue() === 'dislike_challenges') {
                return ($wordNumber -= 2);
            }
        }

        if ($type instanceof StressfulJobType) {
            if ($type->getValue() === 'stressful_job') {
                return ($wordNumber -= 5);
            }
        }

        return $wordNumber;
    }
}