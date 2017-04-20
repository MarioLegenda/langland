<?php

namespace Test\BlueDot;

use Library\Tests\BlueDotTestCase;
use BlueDot\Entity\PromiseInterface;

class PreparedExecutionTest extends BlueDotTestCase
{
    public function testPreparedExecution()
    {
        $this->blueDot->useApi('language');

        $this->blueDot
            ->prepareExecution('simple.select.find_language_by_language', array(
                'language' => 'croatian',
            ))
            ->prepareExecution('simple.select.find_all_languages')
            ->prepareExecution('simple.insert.create_language', array(
                'language' => 'bulgarian'
            ))
            ->prepareExecution('simple.select.find_language_by_language', array(
                'language' => 'bulgarian',
            ));

        $promises = $this->blueDot->executePrepared();

        $this->assertNotEmpty(
            $promises,
            sprintf('Promises should not be empty')
        );

        foreach ($promises as $promise) {
            $this->assertInstanceOf(
                PromiseInterface::class,
                $promise,
                sprintf(
                    '$promises array should be an array of %s objects',
                    PromiseInterface::class
                )
            );

            $this->assertTrue(
                $promise->isSuccess(),
                sprintf(
                    'Query %s failed',
                    $promise->getName()
                )
            );
        }
    }
}