<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Merchant;
use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
        'url_slug' => $faker->regexify('[A-Za-z0-9_]{8}'),
        'merchant_id' => function () {
            return factory(Merchant::class)->create()->id;
        },
        'status' => $faker->randomElement([
            'ready-to-buy',
            'incomplete',
            'purchased',
            'processing',
            'payment-rejected',
            'acknowledged',
            'rejected',
            'unacknowledged',
            'completed',
        ]),
        'is_delivery' => $faker->boolean,
        'customer_name' => $faker->name,
        'customer_email' => $faker->safeEmail,
        'customer_address' => $faker->address,
        'customer_phone' => $faker->phoneNumber,
        'available_time' => $faker->dateTime,
        'rejection_reason' => $faker->sentence(),
        'payment_id' => null,
    ];
});

$factory->state(App\Order::class, 'Cancellable Order', function (Faker $faker) {
    return [
        'status' => $faker->randomElement(['purchased', 'processing']),
        'created_at' => \Carbon\Carbon::parse()->subMinutes(30),
    ];
});

$factory->state(App\Order::class, 'Unprocessed Order', function () {
    return [
        'status' => 'purchased',
        'created_at' => \Carbon\Carbon::parse()->subMinutes(30),
        'order_submitted' => \Carbon\Carbon::parse()->subMinutes(30),
    ];
});

$factory->state(App\Order::class, 'Incomplete Order', function () {
    return [
        'status' => 'incomplete',
        'created_at' => \Carbon\Carbon::parse()->subMinutes(20),
        'order_submitted' => \Carbon\Carbon::parse()->subMinutes(5),
    ];
});

$factory->state(App\Order::class, 'Purchased Order', function () {
    return [
        'status' => 'purchased',
        'created_at' => \Carbon\Carbon::parse()->subMinutes(5),
        'order_submitted' => \Carbon\Carbon::parse()->subMinutes(5),
    ];
});

$factory->state(App\Order::class, 'Payment Rejected', function () {
    return [
        'status' => 'payment-rejected',
        'order_submitted' => \Carbon\Carbon::parse()->subMinutes(5),
    ];
});

$factory->state(App\Order::class, 'Acknowledged Order', function () {
    return [
        'status' => 'acknowledged',
        'order_submitted' => \Carbon\Carbon::parse()->subMinutes(5),
    ];
});

$factory->state(App\Order::class, 'Rejected Order', function () {
    return [
        'status' => 'rejected',
        'order_submitted' => \Carbon\Carbon::parse()->subMinutes(5),
    ];
});

$factory->state(App\Order::class, 'Unacknowledged Order', function () {
    return [
        'status' => 'unacknowledged',
        'order_submitted' => \Carbon\Carbon::parse()->subMinutes(35),
    ];
});

$factory->state(App\Order::class, 'Ready To Buy Order', function () {
    return [
        'status' => 'ready-to-buy',
        'created_at' => \Carbon\Carbon::parse()->subMinutes(30),
        'order_submitted' => \Carbon\Carbon::parse()->subMinutes(30),
    ];
});


$factory->state(App\Order::class, 'Fulfilled', function () {
    return [
        'status' => 'fulfilled',
        'created_at' => \Carbon\Carbon::parse()->subMinutes(30),
        'order_submitted' => \Carbon\Carbon::parse()->subMinutes(30),
    ];
});
