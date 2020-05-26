<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use App\OrderReminder;
use Faker\Generator as Faker;

$factory->define(OrderReminder::class, function (Faker $faker) {
    return [
        'order_id' =>  function () {
            return factory(Order::class)->create()->id;
        },
        'reminder_time' => $faker->randomNumber(2),
        'sent' => $faker->dateTime,
    ];
});
