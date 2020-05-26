<?php

namespace App\Http\Controllers\Admin\Order;

use App\Contract\Repositories\OrderContract;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ViewAllOrdersController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param AuthManager $auth
     * @param OrderContract $orderRepository
     * @return Response
     */
    public function __invoke(Request $request, AuthManager $auth, OrderContract $orderRepository): Response
    {
        $merchant = $auth->user()->merchants()->first();

        $outstanding = $orderRepository->getOrdersByMerchantAndStatsPaginated(
            $merchant->id,
            'purchased',
            'DESC',
            10,
            'outstanding'
        );

        $acknowledgedOrders = $orderRepository->getOrdersByMerchantAndStatsPaginated(
            $merchant->id,
            'acknowledged',
            'DESC',
            10,
            'accepted'
        );

        $rejectedOrders = $orderRepository->getOrdersByMerchantAndStatsPaginated(
            $merchant->id,
            'rejected',
            'DESC',
            10,
            'rejected'
        );

        $fulfilledOrders = $orderRepository->getOrdersByMerchantAndStatsPaginated(
            $merchant->id,
            'fulfilled',
            'DESC',
            10,
            'fulfilled'
        );

        return response()->view(
            'admin.order.view-all',
            [
                'bodyClass' => 'body--auth',
                'merchant' => $merchant,
                'outstanding' => $outstanding,
                'rejected' => $rejectedOrders,
                'accepted' => $acknowledgedOrders,
                'fulfilled' => $fulfilledOrders,
            ]
        );
    }
}
