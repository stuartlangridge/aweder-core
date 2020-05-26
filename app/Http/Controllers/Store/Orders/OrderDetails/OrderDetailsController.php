<?php

namespace App\Http\Controllers\Store\Orders\OrderDetails;

use App\Contract\Service\OrderContract;
use App\Contract\Service\StripeContract;
use App\Http\Controllers\Controller;
use App\Merchant;
use App\Order;
use Illuminate\Http\Request;

class OrderDetailsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param OrderContract $orderService
     *
     * @param StripeContract $stripeService
     * @param Merchant $merchant
     * @param Order $order
     * @return mixed
     */
    public function __invoke(
        Request $request,
        OrderContract $orderService,
        StripeContract $stripeService,
        Merchant $merchant,
        Order $order
    ) {
        $merchant->load('paymentProviders');

        if ($orderService->hasOrderBeenPreviouslySubmitted($order)) {
            $request->session()->flash(
                'error',
                'It appears this order has previously been submitted, please create a new order'
            );

            return redirect()->route('store.menu.view', ['merchant' => $merchant->url_slug]);
        }

        $orderService->updateOrderStatus($order, 'ready-to-buy');

        $stripeDetails = $merchant->stripePayment();

        if ($stripeDetails && !empty($stripeDetails->pivot->data)) {
            $stripeData = json_decode($stripeDetails->pivot->data, true);

            $idempotencyKey = $merchant->salt . '' . $order->url_slug;

            $paymentIntent = $stripeService->createPaymentIntent(
                $order->getOrderTotalForPaymentProvider(),
                $stripeData['stripe_user_id'],
                $idempotencyKey
            );

            if ($paymentIntent !== null) {
                $order->payment_id = $paymentIntent->id;
                $order->save();
            }

            if ($paymentIntent === null) {
                $request->session()->flash(
                    'error',
                    'It appears this order has previously been submitted, please create a new order'
                );

                return redirect()->route('store.menu.view', ['merchant' => $merchant->url_slug]);
            }
        }

        return response()
            ->view(
                'store.order.order-details',
                [
                    'merchant' => $merchant,
                    'order' => $order ?? null,
                    'intentSecret' => $paymentIntent->client_secret ?? null,
                    'showStripeJs' => true,
                    'stripeConnectAccountId' => $stripeService->getPublicPlatformKey(),
                    'stripeMerchantAccountId' => $stripeData['stripe_user_id'] ?? null,
                ]
            );
    }
}
