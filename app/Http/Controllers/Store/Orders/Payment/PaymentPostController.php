<?php

namespace App\Http\Controllers\Store\Orders\Payment;

use App\Contract\Service\EmailServiceContract;
use App\Contract\Service\OrderContract;
use App\Http\Controllers\Controller;
use App\Mail\Customer\OrderPlaced;
use App\Mail\Merchant\OrderPlaced as MerchantOrderPlaced;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentPostController extends Controller
{
    /**
     * @param Request $request
     * @param OrderContract $orderService
     * @param EmailServiceContract $emailService
     * @return RedirectResponse
     */
    public function __invoke(
        Request $request,
        OrderContract $orderService,
        EmailServiceContract $emailService
    ): RedirectResponse {
        /** @var \App\Merchant $merchant */
        $merchant = $request->merchant;

        /** @var \App\Order $order */
        $order = $request->order;

        if ($orderService->hasOrderBeenPreviouslySubmitted($order)) {
            $request->session()->flash(
                'error',
                'It appears this order has previously been submitted, please create a new order'
            );

            return redirect()->route('store.menu.view', ['merchant' => $merchant->url_slug]);
        }

        $orderService->updateOrderStatus($order, 'purchased');

        $emailService->setToAddress($order->customer_email)
            ->sendEmail(new OrderPlaced($order, $merchant));

        $emailService->setToAddress($merchant->contact_email)
            ->sendEmail(new MerchantOrderPlaced($order, $merchant));

        $request->session()->flash('success', 'Your order has been completed');

        return redirect()->route(
            'store.menu.order-thank-you',
            [
                'merchant' => $merchant->url_slug,
                'order' => $order->url_slug,
            ]
        );
    }
}
