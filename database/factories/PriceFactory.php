<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Price;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Price::class, function (Faker $faker) {
    return [
        'price'      => $faker->numberBetween(899, 1299),
        'started_at' => Carbon::now(),
    ];
});
