<?php

namespace App\Http\Controllers\Admin\Order;

use App\Contract\Service\OrderContract;
use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class UpdateOrderController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Order $order
     * @param AuthManager $authManager
     * @param OrderContract $orderService
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Order $order, AuthManager $authManager, OrderContract $orderService)
    {
        $merchant = $authManager->user()->merchants()->first();

        return response()
            ->view('admin.order.view', ['merchant' => $merchant, 'order' => $order, 'bodyClass' => 'body--auth',]);
    }
}
