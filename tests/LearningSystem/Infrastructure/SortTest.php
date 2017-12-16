<?php

namespace LearningSystem\Infrastructure;

use BlueDot\BlueDot;
use LearningSystem\Input\InitialDataParameterBag;
use TestLibrary\ContainerAwareTest;
use LearningSystem\Infrastructure\Sort\Sort;

class SortTest extends ContainerAwareTest
{
    public function test_parameter_bag_sort()
    {
        new BlueDot();
        $toSort = [
            new InitialDataParameterBag([
                'order' => 5
            ]),
            new InitialDataParameterBag([
                'order' => 1
            ]),
            new InitialDataParameterBag([
                'order' => 3
            ]),
            new InitialDataParameterBag([
                'order' => 12
            ]),
            new InitialDataParameterBag([
                'order' => 0
            ]),
            new InitialDataParameterBag([
                'order' => 11
            ]),
            new InitialDataParameterBag([
                'order' => 5
            ]),
            new InitialDataParameterBag([
                'order' => 12
            ]),
        ];

        $sorted = Sort::init()->sortParameterBag('order', $toSort);

        $previous = 0;
        /** @var ParameterBagInterface $s */
        foreach ($sorted as $s) {
            static::assertGreaterThanOrEqual($previous, $s->get('order')['parameter']);
        }
    }
}