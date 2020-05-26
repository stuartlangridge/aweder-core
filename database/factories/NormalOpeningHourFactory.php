<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Merchant;
use App\NormalOpeningHour;
use Faker\Generator as Faker;

$factory->define(NormalOpeningHour::class, function (Faker $faker) {
    return [
        'merchant_id' => function () {
            return factory(Merchant::class)->create()->id;
        },
        'day_of_week' => $faker->numberBetween(1, 7),
        'open_time' => '09:00',
        'close_time' => '17:00',
    ];
});
