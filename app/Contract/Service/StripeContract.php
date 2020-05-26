<?php

namespace App\Contract\Service;

use Stripe\PaymentIntent;
use Stripe\StripeObject;

/**
 * Interface StripeContract
 * @package App\Contract\Service
 */
interface StripeContract
{
    /**
     * changes the api key
     * @param string $merchantApiKey
     * @return void
     */
    public function changeStripeApiKeyToMerchantKey(string $merchantApiKey): void;

    /**
     * Creates the payment intent
     *
     * @param int $amount
     * @param string $stripeAccountId
     * @param string $idempotencyKey
     * @param string $confirmation_method
     * @param string $currency
     *
     * @return PaymentIntent|null
     */
    public function createPaymentIntent(
        int $amount,
        string $stripeAccountId,
        string $idempotencyKey,
        string $confirmation_method = 'manual',
        string $currency = 'gbp'
    ): ?PaymentIntent;

    /**
     * retrieves a payment intent from stripe
     * @param string $paymentIntentId
     * @param string $stripeAccountId
     * @return PaymentIntent|null
     */
    public function retrievePaymentIntent(
        string $paymentIntentId,
        string $stripeAccountId
    ): ?PaymentIntent;

    /**
     * @param int $merchantId
     * @param string $merchantUrlSlug
     * @param string $merchantSalt
     * @return string
     */
    public function createUserStateForStripe(
        int $merchantId,
        string $merchantUrlSlug,
        string $merchantSalt
    ): string;

    /**
     * @param string $comparingState
     * @param int $merchantId
     * @param string $merchantUrlSlug
     * @param string $merchantSalt
     * @return mixed
     */
    public function verifyUserStateForStripe(
        string $comparingState,
        int $merchantId,
        string $merchantUrlSlug,
        string $merchantSalt
    ): bool;

    /**
     * @param string $code
     *
     * @throws \Stripe\Exception\OAuth\OAuthErrorException if the request fails
     *
     * @return StripeObject
     */
    public function getOAuthTokenFromStripeWithCode(string $code): StripeObject;

    /**
     * @param string $clientId
     * @param string $stripeUserId
     * @return StripeObject
     */
    public function deAuthorizeStripe(string $clientId, string $stripeUserId): StripeObject;

    /**
     * @return string
     */
    public function getPublicPlatformKey(): string;

    /**
     * @return string
     */
    public function getClientId(): string;

    /**
     * charges the intent
     * @param string $paymentIntent
     * @param string $stripeUserId
     * @return bool
     */
    public function chargePaymentIntent(string $paymentIntent, string $stripeUserId): bool;
}
