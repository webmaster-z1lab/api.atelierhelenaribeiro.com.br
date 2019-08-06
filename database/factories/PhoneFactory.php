<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Phone;
use Faker\Provider\pt_BR\PhoneNumber;

$faker = Faker\Factory::create();
$faker->addProvider(new PhoneNumber($faker));

$factory->define(Phone::class, function ($faker) {
    return [
        'number'      => $faker->phoneNumberCleared,
        'is_whatsapp' => $faker->boolean(50),
    ];
});
