<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Price;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Price::class, function (Faker $faker) {
    return [
        'price'      => $faker->randomFloat(2, 899.11, 1299.99),
        'started_at' => Carbon::now(),
    ];
});
