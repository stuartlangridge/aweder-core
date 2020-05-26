<?php

namespace App\Providers\Repositories;

use App\Contract\Repositories\OrderContract;
use App\Order;
use App\Repository\OrderRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

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
            $model = $app->make(Order::class);

            $logger = $app->make(LoggerInterface::class);

            return new OrderRepository($model, $logger);
        });
    }
}
