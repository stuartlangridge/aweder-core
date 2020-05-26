<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Merchant;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'merchant_id' =>  function () {
            return factory(Merchant::class)->create()->id;
        },
        'category_id' => $faker->numberBetween(1, 4),
        'title' => $faker->word
    ];
});
