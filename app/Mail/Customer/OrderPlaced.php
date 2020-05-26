<?php

namespace App\Mail\Customer;

use App\Merchant;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var Order
     */
    public $order;

    /**
     * @var Merchant
     */
    public $merchant;

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
        return $this->subject(__('mail.order.placed.customer.subject'))
            ->from($this->merchant->contact_email, $this->merchant->name)
            ->replyTo($this->merchant->contact_email)
            ->view('emails.orders.customer.order-placed');
    }
}
