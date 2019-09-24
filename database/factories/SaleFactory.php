<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Sales\Models\Packing;
use Modules\Sales\Models\PaymentMethod;
use Modules\Sales\Models\PaymentMethods;
use Modules\Sales\Models\Product;
use Modules\Sales\Models\Sale;
use Modules\Sales\Models\Visit;

$factory->define(Sale::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->afterMaking(Sale::class, function (Sale $sale, Faker $faker) {
    /** @var \Modules\Sales\Models\Visit $visit */
    $visit = factory(Visit::class)->create();
    $sale->visit()->associate($visit);
    $sale->customer()->associate($visit->customer_id);
    $sale->seller()->associate($visit->seller_id);
    /** @var \Modules\Sales\Models\Product $product */
    $product = $visit->packing->products->first();
    $sale->product()->associate($product->product_id);
    $sale->fill([
        'date'      => $visit->date,
        'price'     => $product->price,
        'reference' => $product->reference,
        'thumbnail' => $product->thumbnail,
        'size'      => $product->size,
        'color'     => $product->color,
    ]);
});
