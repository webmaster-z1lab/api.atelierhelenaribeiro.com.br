<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator;
use Faker\Provider\Color;
use Modules\Catalog\Models\Template;
use Modules\Stock\Models\Product;
use Modules\Stock\Models\Size;

$faker = Faker\Factory::create();
$faker->addProvider(new Color($faker));

$factory->define(Product::class, function (Generator $faker) {
    return [
        'barcode' => $faker->isbn13,
        'size'    => $faker->randomElement(['P', 'M', 'G', 'GG', 'PLUS1', 'PLUS2', 'PLUS3']),
        'color'   => $faker->safeColorName,
    ];
});

$factory->afterMaking(Product::class, function (Product $product) {
    /** @var Template $template */
    $template = factory(Template::class)->create();
    $size = factory(Size::class)->create();
    $color = factory(\Modules\Stock\Models\Color::class)->create();
    $product->template()->associate($template);
    $product->prices()->associate($template->price);
    $product->color = $color->name;
    $product->size = $size->name;
    $product->reference = implode('-', [$template->reference, $color->reference, $size->reference]);
});
