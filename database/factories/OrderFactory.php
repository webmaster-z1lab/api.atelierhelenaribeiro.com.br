<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Customer\Models\Customer;
use Modules\Order\Models\Order;

$factory->define(Order::class, function (Faker $faker) {
    $event_date = $faker->dateTimeBetween('today', '+6 months');

    return [
        'annotations'   => $faker->text,
        'tracking_code' => $faker->ean8,
        'freight'       => rand(),
        'event_date'    => $event_date,
        'ship_until'    => $faker->dateTimeBetween('now', $event_date),
        'shipped_at'    => $faker->dateTime(),
        'status'        => Order::SHIPPED_STATUS,
    ];
});

$factory->afterMaking(Order::class, function (Order $order, Faker $faker) {
    $order->customer()->associate(factory(Customer::class)->create());
});
