<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Merchant;
use App\User;
use Faker\Generator as Faker;

$factory->define(Merchant::class, function (Faker $faker) {
    return [
        'salt' => Str::random(10),
        'registration_stage' => 0,
        'name' => $faker->name,
        'description' => $faker->words(50, true),
        'contact_number' => $faker->phoneNumber,
        'logo' => $faker->imageUrl(),
        'address' => $faker->address,
        'contact_email' => $faker->safeEmail,
        'url_slug' => $faker->regexify('[A-Za-z0-9_]{8}'),
        'mobile_number' => $faker->phoneNumber,
        'allow_delivery' => $faker->numberBetween(0, 1),
        'allow_collection' => $faker->numberBetween(0, 1),
        'address_name_number' => $faker->secondaryAddress,
        'address_street' => $faker->streetAddress,
        'address_city' => $faker->city,
        'address_county' => $faker->county,
        'address_postcode' => $faker->postcode,
        'delivery_cost' => $faker->numberBetween(100, 500),
        'delivery_radius' => $faker->numberBetween(1, 10),
    ];
});

$factory->state(Merchant::class, 'Delivery Only', function (Faker $faker) {
    return [
        'allow_delivery' => 1,
        'allow_collection' => 0,
        'delivery_cost' => $faker->numberBetween(100, 500),
        'delivery_radius' => $faker->numberBetween(1, 10),
    ];
});

$factory->state(Merchant::class, 'Collection Only', function (Faker $faker) {
    return [
        'allow_delivery' => 0,
        'allow_collection' => 1,
        'delivery_cost' => 0,
        'delivery_radius' => 0,
    ];
});

$factory->state(Merchant::class, 'Both', function (Faker $faker) {
    return [
        'allow_delivery' => 1,
        'allow_collection' => 1,
        'delivery_cost' => $faker->numberBetween(100, 500),
        'delivery_radius' => $faker->numberBetween(1, 10),
    ];
});

$factory->state(Merchant::class, 'Fully Registered', function (Faker $faker) {
    return [
        'registration_stage' => 0,
    ];
});
