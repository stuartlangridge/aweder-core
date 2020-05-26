<?php

namespace App\Providers\Repositories;

use App\Category;
use App\Contract\Repositories\CategoryContract;
use App\Repository\CategoryRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

/**
 * Class CategoryServiceProvider
 * @package App\Providers\Repositories
 */
class CategoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind(CategoryContract::class, function (Application $app) {
            $model = $app->make(Category::class);

            $logger = $app->make(LoggerInterface::class);

            return new CategoryRepository($model, $logger);
        });
    }
}
