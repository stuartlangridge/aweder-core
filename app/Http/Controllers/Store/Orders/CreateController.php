<?php

namespace App\Http\Controllers\Store\Orders;

use App\Contract\Service\OrderContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;

class CreateController extends Controller
{
    /**
     * @param CreateOrderRequest $request
     * @param OrderContract $orderService
     */
    public function __invoke(CreateOrderRequest $request, OrderContract $orderService)
    {
        $merchant = $request->merchant;

        if (!$orderService->doesItemBelongToMerchant($merchant->id, $request->get('item'))) {
            $request->session()->flash('error', 'The item you appear to be adding doesnt belong to this store.');

            return redirect()->route('store.menu.view', [$merchant->url_slug]);
        }

        if (!$request->has('order_no')) {
            $order = $orderService->createNewOrderForMerchant($merchant);
        } else {
            $order = $orderService->retrieveCurrentOrderForMerchant($merchant, $request->get('order_no'));
        }

        if ($orderService->addItemToOrder($order, $merchant, $request->get('item'))) {
            $orderService->updateOrderTotal($order);

            $request->session()->flash('success', 'The item has been added to your order');
        } else {
            $request->session()->flash('error', 'There was an error adding the item to your order');
        }

        return redirect()->route('store.menu.view', [$merchant->url_slug, $order->url_slug]);
    }
}
