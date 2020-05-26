<?php

namespace App\Mail;

use App\Merchant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MerchantSignUp extends Mailable
{
    use Queueable;
    use SerializesModels;

    public Merchant $merchant;

    /**
     * Create a new message instance.
     *
     * @param Merchant $merchant
     */
    public function __construct(Merchant $merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mail.register.awe-der-merchant.subject'))
            ->from(config('mail.hello.address'), config('mail.hello.address'))
            ->view('emails.register.merchant-signup');
    }
}
