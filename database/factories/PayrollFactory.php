<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\Payroll;
use Modules\Sales\Models\Product;
use Modules\Sales\Models\Visit;
use Modules\Stock\Models\ProductStatus;

$factory->define(Payroll::class, function (Faker $faker) {
    return [
        'status' => ProductStatus::ON_CONSIGNMENT_STATUS,
    ];
});

$factory->afterMaking(Payroll::class, function (Payroll $payroll, Faker $faker) {
    /** @var \Modules\Sales\Models\Visit $visit */
    $visit = factory(Visit::class)->create();
    $payroll->visit()->associate($visit);
    $payroll->customer()->associate($visit->customer_id);
    $payroll->seller()->associate($visit->seller_id);
    /** @var \Modules\Sales\Models\Product $product */
    $product = $visit->packing->products->first();
    $payroll->product()->associate($product->product_id);
    $payroll->fill([
        'date'      => $visit->date,
        'price'     => $product->price,
        'reference' => $product->reference,
        'thumbnail' => $product->thumbnail,
        'size'      => $product->size,
        'color'     => $product->color,
    ]);
});
