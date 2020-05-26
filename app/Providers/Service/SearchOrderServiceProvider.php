<?php

namespace App\Providers\Service;

use App\Contract\Repositories\OrderContract;
use App\Contract\Service\SearchOrderContract;
use App\Service\SearchOrderService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class SearchOrderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(SearchOrderContract::class, function (Application $app) {
            $logger = $app->make(LoggerInterface::class);
            $orderRepository = $app->make(OrderContract::class);

            return new SearchOrderService($logger, $orderRepository);
        });
    }
}
