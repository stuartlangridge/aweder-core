<?php

namespace App\Providers\Repositories;

use App\Contract\Repositories\InventoryContract;
use App\Inventory;
use App\Repository\InventoryRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class InventoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(InventoryContract::class, function (Application $app) {
            $model = $app->make(Inventory::class);

            $logger = $app->make(LoggerInterface::class);

            return new InventoryRepository($model, $logger);
        });
    }
}
