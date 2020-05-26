<?php

namespace App\Http\Controllers\Store\Orders\OrderDetails;

use App\Contract\Service\EmailServiceContract;
use App\Contract\Service\OrderContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderPaymentRequest;
use App\Mail\Customer\OrderPlaced;
use App\Mail\Merchant\OrderPlaced as MerchantOrderPlaced;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class OrderDetailsPostController extends Controller
{
    /**
     * @param OrderPaymentRequest $request
     * @param OrderContract $orderService
     * @param EmailServiceContract $emailService
     * @return RedirectResponse
     */
    public function __invoke(
        OrderPaymentRequest $request,
        OrderContract $orderService,
        EmailServiceContract $emailService
    ): RedirectResponse {
        $merchant = $request->merchant;

        $order = $request->order;

        if ($orderService->hasOrderBeenPreviouslySubmitted($order)) {
            $request->session()->flash(
                'error',
                'It appears this order has previously been submitted, please create a new order'
            );

            return redirect()->route('store.menu.view', $merchant->slug_url);
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

        $orderDetails['order_submitted'] = Carbon::now();

        if ($orderService->storeCustomerDetailsOnOrder($order, $orderDetails)) {
            if ($merchant->paymentProviders()->count() > 0) {
                $request->session()->flash('success', 'Please enter payment details');


                return redirect()->route(
                    'store.menu.payment',
                    [
                        'merchant' => $merchant->url_slug,
                        'order' => $order->url_slug,
                    ]
                );
            }
            //This merchant has no payment methods meaning we mark this as paid and direct to payment
            $orderService->updateOrderStatus($order, 'purchased');

            $emailService->setToAddress($request->get('customer_email'))
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

        return redirect()->back();
    }
}
