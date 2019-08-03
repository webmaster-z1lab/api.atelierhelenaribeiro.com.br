<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Address;
use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Address as AddressFaker;

$faker = new Faker();
$faker->addProvider(new AddressFaker($faker));

$factory->define(Address::class, function (Faker $faker) {
    return [
        'street'      => $faker->streetName,
        'number'      => $faker->buildingNumber,
        'complement'  => $faker->secondaryAddress,
        'district'    => $faker->citySuffix,
        'postal_code' => '36520000',
        'city'        => $faker->city,
        'state'       => $faker->stateAbbr,
    ];
});
