<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Phone;
use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Person;
use Modules\Customer\Models\Owner;
use Carbon\Carbon;

$faker = new Faker();
$faker->addProvider(new Person($faker));

$factory->define(Owner::class, function (Faker $faker) {
    return [
        'name'       => $faker->name,
        'document'   => $faker->cpf(FALSE),
        'birth_date' => Carbon::create($faker->numberBetween(1940, 2000), $faker->numberBetween(1,12), $faker->numberBetween(1,28)),
        'email'      => $faker->safeEmail,
    ];
});

$factory->afterMaking(Owner::class, function (Owner $owner) {
    $owner->phone()->associate(factory(Phone::class)->make());
});
