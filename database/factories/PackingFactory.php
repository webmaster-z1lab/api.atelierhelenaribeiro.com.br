<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Employee\Models\EmployeeTypes;
use Modules\Sales\Models\Packing;
use Modules\Stock\Models\Product;
use Modules\User\Models\User;

$factory->define(Packing::class, function (Faker $faker) {
    return [
    ];
});

$factory->afterMaking(Packing::class, function (Packing $packing) {
    $seller = factory(User::class)->state('fake')->create(['type' => EmployeeTypes::TYPE_SELLER]);
    $packing->seller()->associate($seller);
    $products = rand(1, 5);
    for ($i = 0; $i < $products; $i++) {
        $product = factory(Product::class)->create();

        $packing->products()->associate(new \Modules\Sales\Models\Product([
            'product_id' => $product->id,
            'reference'  => $product->reference,
            'thumbnail'  => $product->thumbnail,
            'size'       => $product->size,
            'color'      => $product->color,
            'price'      => $product->price->price,
        ]));
    }
});
