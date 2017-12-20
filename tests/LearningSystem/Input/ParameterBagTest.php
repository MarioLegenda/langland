<?php

namespace Tests\LearningSystem\Input;

use LearningSystem\Input\InitialDataParameterBag;
use LearningSystem\Infrastructure\ParameterBagInterface;
use LearningSystem\Input\Validator\InitialParametersValidator;
use TestLibrary\ContainerAwareTest;
use Tests\LearningSystem\TestHelpers\Questions;

class ParameterBagTest extends ContainerAwareTest
{
    /**
     * @var Questions $questions
     */
    private $questions;
    /**
     * @void
     */
    public function setUp()
    {
        parent::setUp();

        $this->questions = new Questions();
    }

    public function test_initial_parameters_functionality()
    {
        $initialParameterBag = new InitialDataParameterBag();

        $initialParameterBag
            ->add('key_1', 'value_1')
            ->add('key_2', 'value_2')
            ->add('key_3', 'value_3');

        $this->assertInitialParameterBag($initialParameterBag);

        $initialParameterBag = new InitialDataParameterBag([
            'key_1' => 'value_1',
            'key_2' => 'value_2',
            'key_3' => 'value_3',
        ]);

        $this->assertInitialParameterBag($initialParameterBag);
    }

    public function test_initial_parameters_real_usage()
    {
        $bag = new InitialDataParameterBag(
            $this->questions->toArray(),
            [new InitialParametersValidator()]
        );

        static::assertFalse($bag->isEmpty());
    }
    /**
     * @param ParameterBagInterface $initialParameterBag
     */
    private function assertInitialParameterBag(ParameterBagInterface $initialParameterBag)
    {
        static::assertEquals(3, count($initialParameterBag));

        static::assertTrue($initialParameterBag->has('key_1'));
        static::assertTrue($initialParameterBag->has('key_2'));
        static::assertTrue($initialParameterBag->has('key_3'));

        static::assertEquals('value_1', $initialParameterBag->get('key_1')['parameter']);
        static::assertEquals('value_2', $initialParameterBag->get('key_2')['parameter']);
        static::assertEquals('value_3', $initialParameterBag->get('key_3')['parameter']);

        static::assertTrue($initialParameterBag->remove('key_1'));

        static::assertNull($initialParameterBag->get('key_1'));

        static::assertEquals(2, count($initialParameterBag));
    }
}