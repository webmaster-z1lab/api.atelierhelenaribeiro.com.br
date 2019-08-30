<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Stock\Models\Color;

$factory->define(Color::class, function (Faker $faker) {
    return [
        'name'  => $faker->safeColorName,
    ];
});

$factory->afterMaking(Color::class, function (Color $color) {
    do {
        $reference = '';
        for ($i = 0; $i < Color::REFERENCE_LENGTH; $i++) {
            $reference .= rand(0, 9);
        }
    } while (Color::where('reference', $reference)->exists());

    $color->forceFill(['reference' => $reference]);
});
