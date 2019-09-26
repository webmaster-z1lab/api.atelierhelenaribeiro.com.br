<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Faker\Generator as Faker;
use Modules\Customer\Models\Customer;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Models\Information;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Visit;
use Modules\User\Models\User;

$factory->define(Visit::class, function (Faker $faker) {
    return [
        'date' => Carbon::createFromTimestamp($faker->dateTime->getTimestamp()),
        'annotations' => $faker->text
    ];
});

$factory->afterMaking(Visit::class, function (Visit $visit) {
    $customer = factory(Customer::class)->create();
    /** @var \Modules\Sales\Models\Packing $packing */
    $packing = factory(Packing::class)->create();

    $visit->packing()->associate($packing);
    $visit->seller()->associate($packing->seller_id);
    $visit->customer()->associate($customer);
    $visit->sale()->associate(new Information());
    $visit->refund()->associate(new Information());
    $visit->payroll()->associate(new Information());
    $visit->payroll_sale()->associate(new Information());
    $visit->payroll_refund()->associate(new Information());
});
