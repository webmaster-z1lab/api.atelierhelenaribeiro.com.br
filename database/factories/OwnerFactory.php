<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Phone;
use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Person;
use Modules\Customer\Models\Owner;

$faker = new Faker();
$faker->addProvider(new Person($faker));

$factory->define(Owner::class, function (Faker $faker) {
    return [
        'name'       => $faker->name,
        'document'   => $faker->cpf(FALSE),
        'birth_date' => now()->subYears(30),
        'email'      => $faker->safeEmail,
    ];
});

$factory->afterMaking(Owner::class, function (Owner $owner) {
    $owner->phone()->associate(factory(Phone::class)->make());
});
