<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Contract\Repositories\UserContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contract\Repositories\CategoryContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SetupController extends Controller
{
    /**
     * @param Request $request
     * @param CategoryContract $categoryRepository
     * @param \App\Contract\Repositories\UserContract $userRepository
     * @return \Illuminate\View\View
     */
    public function __invoke(
        Request $request,
        CategoryContract $categoryRepository,
        UserContract $userRepository
    ): View {
        $user = $userRepository->getUserWithMerchants(Auth::id());
        $merchant = $user->merchants()->first();

        return view('admin.inventory.index', ['bodyClass' => 'body--auth'])
            ->with(
                'fullInventory',
                $categoryRepository->getCategoryAndInventoryListForUser($user->merchants()->first()->id)
            )
            ->with(
                'merchant',
                $merchant
            )
            ->with(
                'signUpRoute',
                $request->session()->get('signup_route', false)
            );
    }
}
