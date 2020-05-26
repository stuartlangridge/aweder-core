<?php

namespace App\Providers\Service;

use App\Contract\Service\StripeContract;
use App\Service\StripeService;
use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(StripeContract::class, function () {
            $stripeClientId = config('stripe.stripe_client_key');

            $platformApiKey = config('stripe.platform_api_key');

            $stripePublicKey = config('stripe.platform_public_api_key');

            return new StripeService($stripeClientId, $platformApiKey, $stripePublicKey);
        });
    }
}
