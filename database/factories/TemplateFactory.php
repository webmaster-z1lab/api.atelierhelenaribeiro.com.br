<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Modules\Catalog\Models\Template;
use Faker\Generator as Faker;
use Modules\Catalog\Repositories\TemplateRepository as Repository;
use App\Models\Price;


$factory->define(Template::class, function (Faker $faker) {
    return [
        'reference' => (new Repository())->getNewReference(),
    ];
});

$factory->afterMaking(Template::class, function (Template $template) {
    $template->prices()->associate(factory(Price::class)->make());
});
