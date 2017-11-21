<?php

namespace Tests\TestLibrary\DataProvider;

use Faker\Generator;

interface DefaultDataProviderInterface
{
    public function createDefault(Generator $faker);
    public function createDefaultDb(Generator $faker);
}