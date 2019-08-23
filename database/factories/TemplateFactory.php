<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Price;
use Faker\Generator as Faker;
use Modules\Catalog\Models\Template;
use Modules\Catalog\Repositories\TemplateRepository as Repository;
use App\Models\Image;


$factory->define(Template::class, function (Faker $faker) {
    return [
        'reference' => (new Repository())->getNewReference(),
        'is_active' => $faker->boolean(50),
    ];
});

$factory->afterMaking(Template::class, function (Template $template) {
    $template->prices()->associate(factory(Price::class)->make());
});

$factory->afterCreating(Template::class, function (Template $template) {
    $template->images()->saveMany(factory(Image::class, 2)->make(['template_id' => $template->id]));
});
