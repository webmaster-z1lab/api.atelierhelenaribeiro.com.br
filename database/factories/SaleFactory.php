<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Sales\Models\Packing;
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
    $sale->date = $visit->date;
    $sale->visit()->associate($visit);
    $sale->customer()->associate($visit->customer_id);
    $sale->seller()->associate($visit->seller_id);

    /** @var \Modules\Sales\Models\Packing $packing */
    $packing = factory(Packing::class)->create();
    $packing->seller()->associate($visit->seller_id);
    $packing->save();

    $amount = rand(1, $packing->products()->count());
    $sale->total_amount = $amount;
    $total_price = 0;
    $products = $packing->products->take($amount);
    foreach ($products as $product) {
        $sale->products()->associate(new Product([
            'product_id' => $product->product_id,
            'reference' => $product->reference,
            'thumbnail' => $product->thumbnail,
            'size' => $product->size,
            'color' => $product->color,
            'price' => $product->price,
        ]));
        $total_price += $product->price;
    }
    $sale->total_price = $total_price;
    $sale->discount = rand(0, $total_price);
});
