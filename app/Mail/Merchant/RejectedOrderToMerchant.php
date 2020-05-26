<?php

namespace App\Mail\Merchant;

use App\Merchant;
use Illuminate\Bus\Queueable;
use App\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectedOrderToMerchant extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var Merchant
     */
    public $merchant;

    /**
     * @var Order
     */
    public $order;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     * @param Merchant $merchant
     */
    public function __construct(Order $order, Merchant $merchant)
    {
        $this->order = $order;

        $this->merchant = $merchant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->merchant->contact_email)
            ->replyTo($this->replyTo($this->merchant->contact_email))
            ->view('emails.orders.merchant.rejected-order');
    }
}
