<?php

namespace App\Service;

use App\Contract\Service\StripeContract;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\OAuth;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeObject;

class StripeService implements StripeContract
{
    protected string $clientId;

    protected string $publicKey;

    public function __construct(
        string $stripeClientId,
        string $platformApiKey,
        string $stripePublicKey
    ) {
        $this->clientId = $stripeClientId;

        $this->publicKey = $stripePublicKey;

        Stripe::setApiKey($platformApiKey);
    }

    public function changeStripeApiKeyToMerchantKey(string $merchantApiKey): void
    {
        Stripe::setApiKey($merchantApiKey);
    }

    public function createPaymentIntent(
        int $amount,
        string $stripeAccountId,
        string $idempotencyKey,
        string $confirmation_method = 'manual',
        string $currency = 'gbp'
    ): ?PaymentIntent {
        try {
            return PaymentIntent::create(
                [
                    'amount' => $amount,
                    'currency' => $currency,
                    'payment_method_types' => ['card'],
                    'capture_method' => $confirmation_method,
                ],
                [
                    'stripe_account' => $stripeAccountId,
                    'idempotency_key' => $idempotencyKey,
                ]
            );
        } catch (ApiErrorException $exception) {
            Log::error('StripeService::createPaymentIntent error ' . $exception->getMessage());
            return null;
        }
    }

    public function retrievePaymentIntent(string $paymentIntentId, string $stripeAccountId): ?PaymentIntent
    {
        try {
            return PaymentIntent::retrieve(
                $paymentIntentId,
                [
                    'stripe_account' => $stripeAccountId,
                ]
            );
        } catch (ApiErrorException $exception) {
            Log::error('StripeService::retrievePaymentIntent error ' . $exception->getMessage());
            return null;
        }
    }

    public function createUserStateForStripe(int $merchantId, string $merchantUrlSlug, string $merchantSalt): string
    {
        return Hash::make($merchantId . $merchantUrlSlug . $merchantSalt);
    }

    public function verifyUserStateForStripe(
        string $comparingState,
        int $merchantId,
        string $merchantUrlSlug,
        string $merchantSalt
    ): bool {
        return Hash::check(
            $merchantId . $merchantUrlSlug . $merchantSalt,
            $comparingState
        );
    }

    public function getOAuthTokenFromStripeWithCode(string $code): StripeObject
    {
        return OAuth::token(
            [
                'grant_type' => 'authorization_code',
                'code' => $code,
            ]
        );
    }

    public function deAuthorizeStripe(string $clientId, string $stripeUserId): StripeObject
    {
        return OAuth::deauthorize([
            'client_id' => $clientId,
            'stripe_user_id' => $stripeUserId,
        ]);
    }

    public function getPublicPlatformKey(): string
    {
        return $this->publicKey;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function chargePaymentIntent(string $paymentIntent, string $stripeUserId): bool
    {
        $returnedPayment = $this->retrievePaymentIntent($paymentIntent, $stripeUserId);

        if ($returnedPayment === null) {
            return false;
        }

        $updatedIntent = $returnedPayment->capture();

        return $updatedIntent['status'] === 'succeeded';
    }
}
