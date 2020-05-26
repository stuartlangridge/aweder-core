<?php

namespace App\Mail\Merchant;

use App\Merchant;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MerchantOrderReminder extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var Merchant
     */
    public $merchant;

    /**
     * Order
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
        $subject = $this->generateOrderSubjectLine();

        return $this
            ->subject($subject)
            ->from(config('mail.orders.address'), config('mail.orders.name'))
            ->replyTo(config('mail.orders.address'))
            ->view('emails.orders.merchant.order-reminder');
    }

    protected function generateOrderSubjectLine(): string
    {
        return str_replace('<orderid>', $this->order->url_slug, __('mail.order.reminder.merchant.subject'));
    }
}
