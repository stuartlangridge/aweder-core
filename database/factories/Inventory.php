<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Inventory;
use App\Merchant;
use Faker\Generator as Faker;

$factory->define(Inventory::class, function (Faker $faker) {
    return [
        'merchant_id' => function () {
            return factory(Merchant::class)->create()->id;
        },
        'category_id' => function () {
            return factory(Category::class)->create()->id;
        },
        'title' => $faker->word,
        'description' => $faker->word,
        'price' => 400,
        'available' => $faker->numberBetween(0, 1)
    ];
});
