<?php

namespace App\Providers\Repositories;

use App\Contract\Repositories\MerchantContract;
use App\Repository\MerchantRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use App\Merchant;
use Psr\Log\LoggerInterface;

class MerchantServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(MerchantContract::class, function (Application $app) {
            $model = $app->make(Merchant::class);

            $logger = $app->make(LoggerInterface::class);

            return new MerchantRepository($model, $logger);
        });
    }
}
