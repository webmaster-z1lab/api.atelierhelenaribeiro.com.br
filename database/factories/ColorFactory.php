<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Stock\Models\Color;

$factory->define(Color::class, function (Faker $faker) {
    return [
        'name'  => 'turtledove',
        'value' => '#ded7c8',
    ];
});
