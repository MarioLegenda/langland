<?php

namespace LearningSystem\Middleware;

use LearningSystem\Algorithm\Initial\Parameter\AlgorithmParameters;
use LearningSystem\Algorithm\Initial\Parameter\GameTypesCollection;
use LearningSystem\Algorithm\Initial\Parameter\GeneralParameters;
use LearningSystem\Infrastructure\ParameterBagInterface;
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
    }
    /**
     * @param array $questions
     * @return ParameterBagInterface
     */
    private function createAlgorithmParameter(array $questions): ParameterBagInterface
    {
        $bag = new InitialDataParameterBag($questions);

        $parameterConverter = new ParameterConverter($bag);

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