<?php

namespace App\Mail\Customer;

use App\Order;
use App\Merchant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderAccepted extends Mailable
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
    public function __construct(
        Order $order,
        Merchant $merchant
    ) {
        $this->order = $order;

        $this->merchant = $merchant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        if ($this->order->getIsDeliveryOrCollection() === 'Delivery') {
            $view = 'emails.orders.customer.accepted-order-delivery';
        } else {
            $view = 'emails.orders.customer.accepted-order-collection';
        }
        return $this
            ->subject(__('mail.order.accepted.customer.subject'))
            ->from($this->merchant->contact_email, $this->merchant->name)
            ->replyTo($this->merchant->contact_email)
            ->view($view);
    }
}
