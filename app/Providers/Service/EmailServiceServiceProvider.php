<?php

namespace App\Providers\Service;

use App\Contract\Service\EmailServiceContract;
use App\Service\EmailService;
use Illuminate\Support\ServiceProvider;

class EmailServiceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bind(EmailServiceContract::class, function () {
            return new EmailService();
        });
    }
}
