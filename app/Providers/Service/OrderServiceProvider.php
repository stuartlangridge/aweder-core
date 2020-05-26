<?php

namespace App\Providers\Service;

use App\Contract\Repositories\InventoryContract;
use App\Contract\Repositories\MerchantContract;
use App\Contract\Service\OrderContract;
use App\Service\OrderService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use App\Contract\Repositories\OrderContract as OrderRepositoryContract;

class OrderServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(OrderContract::class, function (Application $app) {
            $orderRepo = $app->make(OrderRepositoryContract::class);

            $merchantRepo = $app->make(MerchantContract::class);

            $inventoryRepo = $app->make(InventoryContract::class);

            $logger = $app->make(LoggerInterface::class);

            return new OrderService($orderRepo, $merchantRepo, $inventoryRepo, $logger);
        });
    }
}
