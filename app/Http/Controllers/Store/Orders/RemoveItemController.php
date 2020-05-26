<?php

namespace App\Http\Controllers\Store\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\RemoveOrderItemRequest;
use App\Order;
use App\Service\OrderService;
use Illuminate\Http\RedirectResponse;

class RemoveItemController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param RemoveOrderItemRequest $request
     * @param OrderService $orderService
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RemoveOrderItemRequest $request, OrderService $orderService): RedirectResponse
    {
        $merchant = $request->merchant;

        if ($request->order instanceof Order) {
            $order = $request->order;

            $order->load('items', 'items.inventory');
        }

        if (!$orderService->doesItemBelongToMerchant($merchant->id, $request->get('item'))) {
            $request->session()->flash('error', 'The item you appear to be remove doesnt belong to this store.');

            return redirect()->route('store.menu.view', [$merchant->url_slug, $order->url_slug]);
        }

        $order = $orderService->retrieveCurrentOrderForMerchant($merchant, $request->get('order_no'));

        if ($orderService->removeItemFromOrder($order, $merchant, $request->get('item'))) {
            $orderService->updateOrderTotal($order);

            $request->session()->flash('success', 'The item has been removed to your order');
        } else {
            $request->session()->flash('error', 'There was an error removing the item to your order');
        }

        return redirect()->route('store.menu.view', [$merchant->url_slug, $order->url_slug]);
    }
}
