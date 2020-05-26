<?php

namespace App\Providers\Service;

use App\Contract\Service\MediaServiceContract;
use App\Service\MediaService;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class MediaServiceServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(MediaServiceContract::class, function (Application $app) {
            $fileManger = $app->make(Factory::class);

            $logger = $app->make(LoggerInterface::class);

            return new MediaService($fileManger, $logger);
        });
    }
}
