<?php

namespace App\Http\Controllers\Admin\Order;

use App\Contract\Service\OrderContract;
use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class ViewOrderController extends Controller
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
        if (!$orderService->hasOrderGonePastStage($order, 'processing')) {
            $orderService->updateOrderStatus($order, 'processing');
        }

        $merchant = $authManager->user()->merchants()->first();

        $order->load('items', 'items.orderInventory');

        $thirtyMinutesFromOrderCreationDate = $order->created_at->copy();

        $thirtyMinutesFromOrderCreationDate->addMinutes(30);

        return response()
            ->view(
                'admin.order.view',
                [
                    'bodyClass' => 'body--auth',
                    'merchant' => $merchant,
                    'order' => $order,
                    'predictedOrderTimeHour' => $thirtyMinutesFromOrderCreationDate->format('H'),
                    'predictedOrderTimeMinute' => $thirtyMinutesFromOrderCreationDate->format('i')
                ]
            );
    }
}
