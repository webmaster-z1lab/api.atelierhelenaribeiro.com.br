<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Payroll;
use Modules\Sales\Models\Product;
use Modules\Sales\Models\Visit;

$factory->define(Payroll::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->afterMaking(Payroll::class, function (Payroll $payroll, Faker $faker) {
    /** @var \Modules\Sales\Models\Visit $visit */
    $visit = factory(Visit::class)->create();
    $payroll->date = $visit->date;
    $payroll->visit()->associate($visit);
    $payroll->customer()->associate($visit->customer_id);
    $payroll->seller()->associate($visit->seller_id);

    $amount = rand(1, $visit->packing->products()->count());
    $payroll->total_amount = $amount;
    $total_price = 0;
    $products = $visit->packing->products->take($amount);
    foreach ($products as $product) {
        $payroll->products()->associate(new Product([
            'product_id' => $product->product_id,
            'reference' => $product->reference,
            'thumbnail' => $product->thumbnail,
            'size' => $product->size,
            'color' => $product->color,
            'price' => $product->price,
        ]));
        $total_price += $product->price;
    }
    $payroll->total_price = $total_price;
});
