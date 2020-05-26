<?php

namespace App\Http\Controllers\Store\Orders\Payment;

use App\Contract\Service\OrderContract;
use App\Contract\Service\StripeContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class PaymentController
 * @package App\Http\Controllers\Store\Orders\Payment
 *
 */
class PaymentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param OrderContract $orderService
     *
     * @param StripeContract $stripeService
     * @return mixed
     */
    public function __invoke(Request $request, OrderContract $orderService, StripeContract $stripeService)
    {
        /** @var \App\Merchant $merchant */
        $merchant = $request->merchant;

        $merchant->load('paymentProviders');

        /** @var \App\Order $order */
        $order = $request->order;

        if ($orderService->hasOrderBeenPreviouslySubmitted($order)) {
            $request->session()->flash(
                'error',
                'It appears this order has previously been submitted, please create a new order'
            );

            return redirect()->route('store.menu.view', ['merchant' => $merchant->url_slug]);
        }

        $stripeDetails = $merchant->stripePayment();

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

        return response()
            ->view(
                'store.payment.payment-details',
                [
                    'merchant' => $merchant,
                    'order' => $order ?? null,
                    'intentSecret' => $paymentIntent->client_secret,
                    'showStripeJs' => true,
                    'stripeConnectAccountId' => $stripeService->getPublicPlatformKey(),
                    'stripeMerchantAccountId' => $stripeData['stripe_user_id'],
                ]
            );
    }
}
