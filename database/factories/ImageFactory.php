<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Image;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    $name = $faker->name;

    return [
        'path'          => "test/$name.webp",
        'extension'     => 'webp',
        'name'          => "$name.webp",
        'size_in_bytes' => $faker->numberBetween(30000, 523896),
        'mime_type'     => 'image/webp',
    ];
});
