<?php

namespace Tests\LearningSystem\Infrastructure;

use LearningSystem\Algorithm\Initial\Type\FreeTimeType;
use TestLibrary\ContainerAwareTest;

class TypesTest extends ContainerAwareTest
{
    public function test__type()
    {
        $freeTimeType = FreeTimeType::fromValue('1 hour');

        static::assertInstanceOf(FreeTimeType::class, $freeTimeType);

        static::assertInstanceOf(FreeTimeType::class, $freeTimeType);
        static::assertEquals(2, $freeTimeType->getKey());
        static::assertEquals('1 hour', $freeTimeType->getValue());
        static::assertTrue($freeTimeType->isTypeByKey(2));
        static::assertTrue($freeTimeType->isTypeByValue('1 hour'));

        $freeTimeType = FreeTimeType::fromKey(2);

        static::assertInstanceOf(FreeTimeType::class, $freeTimeType);

        static::assertInstanceOf(FreeTimeType::class, $freeTimeType);
        static::assertEquals(2, $freeTimeType->getKey());
        static::assertEquals('1 hour', $freeTimeType->getValue());
        static::assertTrue($freeTimeType->isTypeByKey(2));
        static::assertTrue($freeTimeType->isTypeByValue('1 hour'));

        static::assertTrue($freeTimeType->inValueRange(['1 hour', ['2 hours']]));
        static::assertFalse($freeTimeType->inValueRange(['mile', 'kreten']));

        static::assertTrue($freeTimeType->inKeyRange([1, 6, 8, 2]));
        static::assertFalse($freeTimeType->inKeyRange([1, 6, 8]));

        static::assertTrue($freeTimeType->equalsKey(2));

        $freeTime1 = FreeTimeType::fromValue('1 hour');
        $freeTime2 = FreeTimeType::fromKey(2);

        static::assertTrue($freeTime1->equals($freeTime2));
        static::assertTrue($freeTime2->equals($freeTime1));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_person_type_failure_from_value()
    {
        FreeTimeType::fromValue('mile');
    }
}