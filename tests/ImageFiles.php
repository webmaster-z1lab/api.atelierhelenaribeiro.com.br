<?php

namespace Tests;

use Faker\Generator as Faker;
use Faker\Provider\Base;
use Faker\Provider\pt_BR\Person;

trait ImageFiles
{
    /**
     * @return array
     */
    public function getImages(): array
    {
        $faker = new Faker();
        $faker->addProvider(new Base($faker));
        $faker->addProvider(new Person($faker));

        $images = [];
        $total = $faker->numberBetween(1, 5);

        for ($i = 0; $i < $total; $i++) {
            $name = $faker->name;

            $images[] = [
                'path'          => "/test/$name.webp",
                'extension'     => 'webp',
                'name'          => "$name.webp",
                'size_in_bytes' => $faker->numberBetween(30000, 523896),
            ];
        }

        return $images;
    }

    /**
     * @return array
     */
    public function getImage(): array
    {
        $faker = new Faker();
        $faker->addProvider(new Base($faker));
        $faker->addProvider(new Person($faker));

        $name = $faker->name;

        return [
            'path'          => "/test/$name.webp",
            'extension'     => 'webp',
            'name'          => "$name.webp",
            'size_in_bytes' => $faker->numberBetween(30000, 523896),
        ];
    }
}
