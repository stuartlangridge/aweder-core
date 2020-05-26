<?php

namespace App\Providers\Repositories;

use App\Contract\Repositories\UserContract;
use App\Repository\UserRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use App\User;
use Psr\Log\LoggerInterface;

class UserServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(UserContract::class, function (Application $app) {
            $model = $app->make(User::class);

            $logger = $app->make(LoggerInterface::class);

            return new UserRepository($model, $logger);
        });
    }
}
