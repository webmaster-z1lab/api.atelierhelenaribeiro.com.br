<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Paycheck\Models\Paycheck;

$factory->define(Paycheck::class, function (Faker $faker) {
    return [
        'holder'   => $faker->name,
        'document' => $faker->cpf(FALSE),
        'bank'     => $faker->company,
        'number'   => $faker->randomNumber(5),
        'pay_date' => $faker->dateTime('+ 3 months'),
        'value'    => rand(1, NULL),
    ];
});
