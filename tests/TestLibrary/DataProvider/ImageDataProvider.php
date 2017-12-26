<?php

namespace Tests\TestLibrary\DataProvider;

use AdminBundle\Entity\Image;
use Faker\Generator;

class ImageDataProvider implements DefaultDataProviderInterface
{
    /**
     * @inheritdoc
     */
    public function createDefault(Generator $faker)
    {
        $image = new Image();

        $image->setTargetDir($faker->word);
        $image->setFullPath($faker->word);
        $image->setOriginalName($faker->name);
        $image->setRelativePath($faker->word);

        return $image;
    }

    public function createDefaultDb(Generator $faker)
    {
        // NOT IMPLEMENTED
    }
}