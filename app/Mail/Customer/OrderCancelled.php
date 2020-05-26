<?php

namespace App\Mail\Customer;

use App\Merchant;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCancelled extends Mailable
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
     * @param Merchant $merchant
     * @param Order $order
     */
    public function __construct(Merchant $merchant, Order $order)
    {
        $this->merchant = $merchant;

        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(__('mail.order.cancelled.customer.subject'))
            ->from($this->merchant->contact_email, $this->merchant->name)
            ->replyTo($this->merchant->contact_email)
            ->view('emails.orders.customer.cancelled-order');
    }
}
