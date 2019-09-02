<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Faker\Generator as Faker;
use Modules\Customer\Models\Customer;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Models\Visit;
use Modules\User\Models\User;

$factory->define(Visit::class, function (Faker $faker) {
    return [
        'date' => Carbon::createFromTimestamp($faker->dateTime->getTimestamp()),
        'annotations' => $faker->sentence
    ];
});

$factory->afterMaking(Visit::class, function (Visit $visit) {
    $seller = factory(User::class)->state('fake')->create(['type' => EmployeeTypes::TYPE_SELLER]);
    $customer = factory(Customer::class)->create();

    $visit->seller()->associate($seller);
    $visit->customer()->associate($customer);
});
