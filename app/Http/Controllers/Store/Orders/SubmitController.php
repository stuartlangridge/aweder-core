<?php

namespace App\Http\Controllers\Store\Orders;

use App\Contract\Service\OrderContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitOrderRequest;
use App\Merchant;
use App\Order;
use Carbon\Carbon;

class SubmitController extends Controller
{
    /**
     * @param SubmitOrderRequest $request
     * @param Merchant $merchant
     * @param Order $order
     * @param OrderContract $orderService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(
        SubmitOrderRequest $request,
        Merchant $merchant,
        Order $order,
        OrderContract $orderService
    ) {
        if ($orderService->hasOrderBeenPreviouslySubmitted($order)) {
            $request->session()->flash(
                'error',
                'It appears this order has previously been submitted, please create a new order'
            );

            return redirect()->route('store.menu.view', ['merchant' => $merchant->url_slug]);
        }

        $orderDetails = $request->validated();

        if (in_array($request->get('collection_type'), ['both', 'delivery'])) {
            $orderDetails['delivery_cost'] = $merchant->delivery_cost;
            $orderDetails['is_delivery'] = 1;
        }

        if (!empty($request->get('order_time'))) {
            $time = $request->get('order_time');

            $orderRequestedTime = Carbon::createFromTime($time['hour'], $time['minute']);

            $orderDetails['customer_requested_time'] = $orderRequestedTime->format('Y-m-d H:i');
        }

        $orderService->storeCustomerDetailsOnOrder($order, $orderDetails);

        return redirect()->route(
            'store.menu.order-details',
            [
                'merchant' => $merchant->url_slug,
                'order' => $order->url_slug,
            ]
        );
    }
}
