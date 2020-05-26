<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MerchantPayment;
use Faker\Generator as Faker;

$factory->define(MerchantPayment::class, function (Faker $faker) {
    return [
        'data' => json_encode([
            'publishable_api_key' => $faker->uuid,
            'secret_api_key' => $faker->uuid,
        ], JSON_THROW_ON_ERROR, 512),
        'merchant_id' => $faker->uuid,
        'provider_id' => $faker->uuid
    ];
});
