<?php

namespace App\Http\Controllers\Auth\Registration\Categories;

use App\Contract\Repositories\CategoryContract;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Response;

class SetupController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param AuthManager $auth
     * @param CategoryContract $categoryRepo
     * @return Response
     */
    public function __invoke(AuthManager $auth, CategoryContract $categoryRepo): Response
    {
        $merchant = $auth->user()->merchants()->first();

        $categories = $merchant->categories;

        $defaultCategories = config('categories.default');

        return response()->view(
            'auth.registration.categories.index',
            [
                'categories' => $categories,
                'bodyClass' => 'body--auth',
                'defaultCategories' => $defaultCategories,
            ]
        );
    }
}
