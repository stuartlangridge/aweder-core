<?php

namespace App\Mail\Merchant;

use App\Merchant;
use App\Order;
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
            $view = 'emails.orders.merchant.accepted-order-delivery';
        } else {
            $view = 'emails.orders.merchant.accepted-order-collection';
        }

        return $this
            ->subject(__('mail.order.accepted.merchant.subject'))
            ->from(config('mail.orders.address'), config('mail.orders.name'))
            ->replyTo(config('mail.orders.address'))
            ->view($view);
    }
}
