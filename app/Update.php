<?php

namespace App;

use MadWeb\Initializer\Contracts\Runner;

class Update
{
    public function production(Runner $run)
    {
        $run->external('composer', 'install', '--no-dev', '--prefer-dist', '--optimize-autoloader')
            ->artisan('migrate', ['--force' => true])
            ->artisan('cache:clear')
            ->artisan('route:cache')
            ->artisan('config:cache')
            ->artisan('event:cache')
            ->artisan('queue:restart'); // ->artisan('horizon:terminate');
    }

    public function testing(Runner $run)
    {
        $run->external('composer', 'install')
            ->artisan('migrate')
            ->artisan('cache:clear');
    }

    public function local(Runner $run)
    {
        $run->external('composer', 'install')
            ->artisan('migrate')
            ->artisan('cache:clear');
    }
}
