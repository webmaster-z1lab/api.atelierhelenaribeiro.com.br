<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Catalog\Models\Template;
use Modules\Stock\Models\Color;
use Modules\Stock\Models\Product;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'barcode' => $faker->isbn13,
        'size' => $faker->randomElement(['P', 'M', 'G', 'GG'])
    ];
});

$factory->afterMaking(Product::class, function (Product $product) {
    $template = factory(Template::class)->create();
    $product->template()->associate($template);
    $product->prices()->associate($template->price);
    $product->color()->associate(factory(Color::class)->make());
});
