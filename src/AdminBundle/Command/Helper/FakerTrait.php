<?php

namespace AdminBundle\Command\Helper;

require_once __DIR__.'/../../../../vendor/fzaninotto/faker/src/autoload.php';

trait FakerTrait
{
    /**
     * @var $faker
     */
    private $faker;
    /**
     * @return \Faker\Generator
     */
    public function getFaker()
    {
        if (is_object($this->faker)) {
            return $this->faker;
        }

        $this->faker = \Faker\Factory::create();

        return $this->faker;
    }
}