<?php

namespace App\Providers\Service;

use App\Contract\Repositories\MerchantContract;
use App\Contract\Repositories\UserContract;
use App\Contract\Service\RegistrationContract;
use App\Service\RegistrationService;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class RegistrationServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(RegistrationContract::class, function (Application $app) {
            $merchantRepo = $app->make(MerchantContract::class);

            $userRepo = $app->make(UserContract::class);

            $logger = $app->make(LoggerInterface::class);

            $fileManger = $app->make(Factory::class);

            return new RegistrationService($merchantRepo, $userRepo, $logger, $fileManger);
        });
    }
}
