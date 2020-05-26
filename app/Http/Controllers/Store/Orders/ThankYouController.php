<?php

namespace App\Http\Controllers\Store\Orders;

use App\Contract\Service\OrderContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThankYouController extends Controller
{
    /**
     * @param Request $request
     * @param OrderContract $orderService
     */
    public function __invoke(Request $request, OrderContract $orderService)
    {
        $merchant = $request->merchant;

        $order = $request->order;

        return response()->view(
            'store.order.thanks',
            [
                'merchant' => $merchant,
                'order' => $order,
            ]
        );
    }
}
