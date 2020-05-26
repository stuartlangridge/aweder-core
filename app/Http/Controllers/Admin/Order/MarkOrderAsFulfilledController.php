<?php

namespace App\Http\Controllers\Admin\Order;

use App\Contract\Service\StripeContract;
use App\Contract\Service\OrderContract;
use App\Http\Controllers\Controller;
use App\Order;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MarkOrderAsFulfilledController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Order $order
     * @param OrderContract $orderService
     * @param StripeContract $stripeService
     * @param AuthManager $auth
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(
        Request $request,
        Order $order,
        OrderContract $orderService,
        StripeContract $stripeService,
        AuthManager $auth
    ): RedirectResponse {
        if (!$orderService->hasOrderGonePastStage($order, 'processing')) {
            $request->session()->flash('error', 'The order needs to be accepted first');

            return redirect()->back();
        }

        if ($order->payment_id !== null) {
            /** @var \App\Merchant $merchant */
            $merchant = $auth->user()->merchants->first();

            $merchant->load('paymentProviders');

            $stripeDetails = $merchant->stripePayment();

            $stripeData = json_decode($stripeDetails->pivot->data, true);

            if (!$stripeService->chargePaymentIntent($order->payment_id, $stripeData['stripe_user_id'])) {
                $request->session()->flash(
                    'error',
                    'There was an error taking the payment please check your stripe account'
                );

                return redirect()->back();
            }
        }

        if (!$orderService->updateOrderStatus($order, 'fulfilled')) {
            $request->session()->flash('error', 'There was an error marking the order as fulfilled');

            return redirect()->back();
        }

        $request->session()->flash(
            'success',
            'The order has been marked as fulfilled'
        );

        return redirect()->route('admin.dashboard');
    }
}
