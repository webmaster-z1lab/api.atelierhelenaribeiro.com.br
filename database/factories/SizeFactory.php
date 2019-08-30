<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Stock\Models\Size;

$factory->define(Size::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement(['P', 'M', 'G', 'GG', 'Plus 1', 'Plus 2', 'Plus 3'])
    ];
});

$factory->afterMaking(Size::class, function (Size $size) {
    do {
        $reference = '';
        for ($i = 0; $i < Size::REFERENCE_LENGTH; $i++) {
            $reference .= rand(0, 9);
        }
    } while (Size::where('reference', $reference)->exists());

    $size->forceFill(['reference' => $reference]);
});
