<?php

namespace App\Providers\Repositories;

use App\Contract\Repositories\NormalOpeningHoursContract;
use App\NormalOpeningHour;
use App\Repository\NormalOpeningHoursRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class NormalOpeningHoursServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(NormalOpeningHoursContract::class, function (Application $app) {
            $model = $app->make(NormalOpeningHour::class);

            $logger = $app->make(LoggerInterface::class);

            return new NormalOpeningHoursRepository($model, $logger);
        });
    }
}
