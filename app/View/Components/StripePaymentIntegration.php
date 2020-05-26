<?php

namespace App\View\Components;

use App\Contract\Service\StripeContract;
use App\Merchant;
use Illuminate\View\Component;

/**
 * Class StripePaymentIntegration
 * @package App\View\Components
 */
class StripePaymentIntegration extends Component
{
    protected Merchant $merchant;

    protected StripeContract $stripeService;
    /**
     * Create a new component instance.
     *
     * @param Merchant $merchant
     * @param StripeContract $stripeService
     */
    public function __construct(Merchant $merchant, StripeContract $stripeService)
    {
        $this->stripeService = $stripeService;

        $this->merchant = $merchant;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.stripe-payment-integration')
            ->with('merchant', $this->merchant)
            ->with('status', $this->generateStripeStatusForMerchant())
            ->with('stripeClientId', $this->stripeService->getClientId());
    }

    /**
     * creates the stripe string
     * @return string
     */
    protected function generateStripeStatusForMerchant(): string
    {
        return $this->stripeService->createUserStateForStripe(
            $this->merchant->id,
            $this->merchant->url_slug,
            $this->merchant->salt
        );
    }
}
