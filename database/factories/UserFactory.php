<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\User\Models\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => 'João das Neves',
        'email' => 'chr@z1lab.com.br',
        'document' => '32489294059',
        'email_verified_at' => now(),
        'password' => Hash::make('12345678'),
        'type' => 'admin'
    ];
});

$factory->state(User::class, 'fake', function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'document' => $faker->cpf(false),
        'email_verified_at' => $faker->dateTime,
        'type' => 'admin',
    ];
});
