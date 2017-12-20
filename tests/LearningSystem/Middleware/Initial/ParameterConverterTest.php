<?php

namespace LearningSystem\Middleware;

use LearningSystem\Algorithm\Initial\Parameter\AlgorithmParameters;
use LearningSystem\Algorithm\Initial\Parameter\GameType\ImageWordGameTypeParameter;
use LearningSystem\Algorithm\Initial\Parameter\GameType\SentenceGameTypeParameter;
use LearningSystem\Algorithm\Initial\Parameter\GameTypesCollection;
use LearningSystem\Algorithm\Initial\Parameter\GeneralParameters;
use LearningSystem\Infrastructure\ParameterBagInterface;
use LearningSystem\Infrastructure\Sort\Sort;
use LearningSystem\Infrastructure\Sort\Type\ParameterBagSort;
use LearningSystem\Infrastructure\Type\TimeTrialType;
use LearningSystem\Input\InitialDataParameterBag;
use TestLibrary\ContainerAwareTest;
use Tests\LearningSystem\TestHelpers\Questions;
use LearningSystem\Middleware\Initial\ParameterConverter;

class ParameterConverterTest extends ContainerAwareTest
{
    /**
     * @var Questions $questions
     */
    private $questions;

    public function setUp()
    {
        parent::setUp();

        $this->questions = new Questions();
    }

    public function test_initial_values_existence()
    {
        $algorithmParameters = $this->createAlgorithmParameter($this->questions->toArray());

        static::assertTrue($algorithmParameters->has('general_parameters'));
        static::assertInternalType('array', $algorithmParameters->get('general_parameters'));

        $generalParameters = $algorithmParameters->get('general_parameters');

        static::assertArrayHasKey('parameter', $generalParameters);

        $parameter = $generalParameters['parameter'];

        static::assertArrayHasKey('word_number', $parameter);
        static::assertArrayHasKey('word_level', $parameter);

        static::assertTrue($algorithmParameters->has('game_types'));
        static::assertInternalType('array', $algorithmParameters->get('game_types'));

        $gameTypesParameter = $algorithmParameters->get('game_types');

        static::assertArrayHasKey('parameter', $gameTypesParameter);

        $sortedGameTypes = $gameTypesParameter['parameter'];

        static::assertEquals(3, count($sortedGameTypes));

        /** @var ParameterBagInterface $imageWordGameParameter1 */
        $imageWordGameParameter1 = $sortedGameTypes[0];

        static::assertInstanceOf(ImageWordGameTypeParameter::class, $imageWordGameParameter1);
        static::assertEquals(1, $imageWordGameParameter1->get('word_level')['parameter']);
        static::assertNull($imageWordGameParameter1->get('time_trial')['parameter']);
        static::assertArrayHasKey('repeat', $imageWordGameParameter1);
        static::assertEquals(0, $imageWordGameParameter1->get('order')['parameter']);

        /** @var ParameterBagInterface $sentenceGameParameter */
        $sentenceGameParameter = $sortedGameTypes[1];

        static::assertInstanceOf(SentenceGameTypeParameter::class, $sentenceGameParameter);
        static::assertEquals(1, $sentenceGameParameter->get('word_level')['parameter']);
        static::assertNull($sentenceGameParameter->get('time_trial')['parameter']);
        static::assertArrayHasKey('repeat', $sentenceGameParameter);
        static::assertEquals(1, $sentenceGameParameter->get('order')['parameter']);

        /** @var ParameterBagInterface $imageWordGameParameter2 */
        $imageWordGameParameter2 = $sortedGameTypes[2];

        static::assertInstanceOf(ImageWordGameTypeParameter::class, $imageWordGameParameter2);
        static::assertEquals(1, $imageWordGameParameter2->get('word_level')['parameter']);
        static::assertInstanceOf(TimeTrialType::class, $imageWordGameParameter2->get('time_trial')['parameter']);
        static::assertArrayHasKey('repeat', $imageWordGameParameter2);
        static::assertEquals(2, $imageWordGameParameter2->get('order')['parameter']);
    }

    public function test_general_parameters_resolving()
    {
        $this->questions->setQuestion('speaking_languages', 1);

        $this->assertOnlySpeakingLanguages($this->questions);

        $this->questions->setQuestion('stressful_job', 1);

        $this->assertStressfulJobWithinLimits($this->questions);

        $this->questions
            ->setQuestion('challenges', 1)
            ->setQuestion('stressful_job', 1);

        $this->assertStressfulJobInitiate($this->questions);

        $this->questions
            ->setQuestion('free_time', 2);

        $this->assertFreeTimeWithPreviousParameters($this->questions);

        $this->questions
            ->setQuestion('challenges', 0)
            ->setQuestion('person_type', 1)
            ->setQuestion('free_time', 1);

        $this->assertMinimumWordNumberLimit($this->questions);

        $this->questions->setQuestion('challenges', 1);

        $this->assertIs18AfterCrossingMinimumWordNumberLimit($this->questions);
    }
    /**
     * @param Questions $questions
     */
    private function assertIs18AfterCrossingMinimumWordNumberLimit(Questions $questions)
    {
        $algorithmParameters = $this->createAlgorithmParameter($questions->toArray());

        $wordNumber = $algorithmParameters->get('general_parameters')['parameter']['word_number'];
        static::assertInternalType('int', $wordNumber);
        static::assertEquals(18, $wordNumber);
    }
    /**
     * @param Questions $questions
     */
    private function assertMinimumWordNumberLimit(Questions $questions)
    {
        $algorithmParameters = $this->createAlgorithmParameter($questions->toArray());

        $wordNumber = $algorithmParameters->get('general_parameters')['parameter']['word_number'];
        static::assertInternalType('int', $wordNumber);
        static::assertEquals(15, $wordNumber);
    }
    /**
     * @param Questions $questions
     */
    private function assertFreeTimeWithPreviousParameters(Questions $questions)
    {
        $algorithmParameters = $this->createAlgorithmParameter($questions->toArray());

        $wordNumber = $algorithmParameters->get('general_parameters')['parameter']['word_number'];
        static::assertInternalType('int', $wordNumber);
        static::assertEquals(21, $wordNumber);
    }
    /**
     * @param Questions $questions
     */
    private function assertStressfulJobInitiate(Questions $questions)
    {
        $algorithmParameters = $this->createAlgorithmParameter($questions->toArray());

        $wordNumber = $algorithmParameters->get('general_parameters')['parameter']['word_number'];
        static::assertInternalType('int', $wordNumber);
        static::assertEquals(18, $wordNumber);
    }
    /**
     * @param Questions $questions
     */
    private function assertOnlySpeakingLanguages(Questions $questions)
    {
        $algorithmParameters = $this->createAlgorithmParameter($questions->toArray());

        $wordNumber = $algorithmParameters->get('general_parameters')['parameter']['word_number'];
        static::assertInternalType('int', $wordNumber);
        static::assertEquals(19, $wordNumber);
    }
    /**
     * @param Questions $questions
     */
    private function assertStressfulJobWithinLimits(Questions $questions)
    {
        $algorithmParameters = $this->createAlgorithmParameter($questions->toArray());

        $wordNumber = $algorithmParameters->get('general_parameters')['parameter']['word_number'];
        static::assertInternalType('int', $wordNumber);
        static::assertEquals(19, $wordNumber);
    }
    /**
     * @param array $questions
     * @return ParameterBagInterface
     */
    private function createAlgorithmParameter(array $questions): ParameterBagInterface
    {
        $bag = new InitialDataParameterBag($questions);

        $parameterConverter = new ParameterConverter();

        $gameTypes = new GameTypesCollection(
            'game_types',
            $bag,
            ['general_parameters']
        );

        $parameterConverter
            ->attach($gameTypes)
            ->attach(new GeneralParameters('general_parameters', $bag));

        $parameterConverter->notify();

        $algorithmParameters = new AlgorithmParameters($parameterConverter);

        return $algorithmParameters;
    }
}