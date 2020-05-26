<?php

namespace App\View\Components;

use App\Merchant;
use App\Order;
use Illuminate\View\Component;

class StripePaymentComponent extends Component
{
    protected Order $order;

    protected Merchant $merchant;

    protected string $stripeConnectAccountId;

    protected string $stripeMerchantAccountId;

    /**
     * Create a new component instance.
     *
     * @param Order $order
     * @param Merchant $merchant
     * @param string $stripeConnectAccountId
     * @param string $stripeMerchantAccountId
     */
    public function __construct(
        Order $order,
        Merchant $merchant,
        string $stripeConnectAccountId,
        string $stripeMerchantAccountId
    ) {
        $this->order = $order;

        $this->merchant = $merchant;

        $this->stripeConnectAccountId = $stripeConnectAccountId;

        $this->stripeMerchantAccountId = $stripeMerchantAccountId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.stripe-payment')
            ->with('order', $this->order)
            ->with('merchant', $this->merchant);
    }
}
