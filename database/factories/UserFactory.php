<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Modules\Employee\Models\EmployeeTypes;
use Modules\User\Models\User;
use App\Models\Address;
use App\Models\Phone;

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

$types = [
    EmployeeTypes::TYPE_ADMIN,
    EmployeeTypes::TYPE_DRESSMAKER,
    EmployeeTypes::TYPE_SELLER,
];

$factory->define(User::class, function (Faker $faker) {
    return [
        'name'     => 'João das Neves',
        'email'    => 'chr@z1lab.com.br',
        'document' => '32489294059',
        'password' => Hash::make('12345678'),
        'type'     => 'admin',
    ];
});

$factory->afterMaking(User::class, function ($user, $faker) {
    $user->address()->associate(factory(Address::class)->make());
    $user->phone()->associate(factory(Phone::class)->make());
});

$factory->state(User::class, 'fake', function (Faker $faker) use ($types) {
    return [
        'name'              => $faker->name,
        'email'             => $faker->email,
        'document'          => $faker->cpf(FALSE),
        'email_verified_at' => $faker->dateTime,
        'type'              => Arr::random($types),
    ];
});
