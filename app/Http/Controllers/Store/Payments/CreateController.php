<?php

namespace App\Http\Controllers\Store\Payments;

use App\Contract\Service\StripeContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Stripe;

class CreateController extends Controller
{
    /**
     * @param StripeContract $stripeService
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function __invoke(StripeContract $stripeService, Request $request): JsonResponse
    {
        $merchant = $request->merchant;

        $paymentMethod = $merchant->paymentProviders()->first();

        $data = json_decode($paymentMethod, true, 512, JSON_THROW_ON_ERROR);

        Stripe::setApiKey($data['secret_api_key']);

        $amount = $request->get('amount');

        $payment_method_id = $request->get('payment_method_id');

        $intent = $stripeService->createPaymentIntent($payment_method_id, $amount);

        if (!$intent['success']) {
            return response()->json([
                'result' => false,
                'error' => 'There was an error creating payment'
            ]);
        }

        $response = [
            'result' => $intent['success'],
            'intent' => [
                'id' => $intent['intent']->id,
                'action' => $intent['intent']->status,
                'secret' => $intent['intent']->client_secret
            ]
        ];

        return response()->json($response);
    }
}
