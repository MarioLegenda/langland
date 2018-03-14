<?php

namespace PublicApi\LearningSystem\DataDecider;

use LearningSystem\Infrastructure\Type\GameType\BasicGameType;
use LearningSystem\Library\DataProviderInterface;
use PublicApi\LearningSystem\DataProvider\Word\ProvidedWordDataCollection;
use PublicApi\LearningSystem\QuestionAnswersApplicationProvider;

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
     * @return array
     */
    public function getData(): array
    {
        $questionAnswers = $this->questionAnswersApplicationResolver->resolve();

        $wordNumber = 20;
        $wordLevel = 1;

        /** @var ProvidedWordDataCollection $dataCollection */
        $dataCollection = $this->wordDataProvider->getData([
            'word_number' => $wordNumber,
            'word_level' => $wordLevel,
        ]);

        return [
            'game_type' => BasicGameType::fromValue(BasicGameType::getName()),
            'data' => $dataCollection,
        ];
    }
}