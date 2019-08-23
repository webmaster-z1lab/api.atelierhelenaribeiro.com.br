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
        'name'           => 'João das Neves',
        'email'          => 'chr@z1lab.com.br',
        'document'       => '32489294059',
        'identity'       => '21212215',
        'work_card'      => '136454313485',
        'remuneration'   => 20,
        'birth_date'     => \Carbon\Carbon::create(1992, 5, 13),
        'admission_date' => \Carbon\Carbon::create(2019, 8, 14),
        'password'       => Hash::make('12345678'),
        'type'           => 'admin',
    ];
});

$factory->afterMaking(User::class, function (User $user) {
    $user->address()->associate(factory(Address::class)->make());
    $user->phone()->associate(factory(Phone::class)->make());
});

$factory->state(User::class, 'fake', function (Faker $faker) use ($types) {
    return [
        'name'              => $faker->name,
        'email'             => $faker->email,
        'document'          => $faker->cpf(FALSE),
        'identity'          => Str::random(),
        'work_card'         => Str::random(),
        'remuneration'      => $faker->randomFloat(2, 0.02, 5000.99),
        'birth_date'        => $faker->dateTime('today - 18 years'),
        'admission_date'    => $faker->dateTime('today'),
        'email_verified_at' => $faker->dateTime,
        'type'              => Arr::random($types),
    ];
});
