<?php

namespace App\Providers\Service;

use App\Contract\Service\InventoryContract;
use App\Service\InventoryService;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

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
            $fileSystemManager = $app->make(FilesystemManager::class);
            return new InventoryService($fileSystemManager);
        });
    }
}
