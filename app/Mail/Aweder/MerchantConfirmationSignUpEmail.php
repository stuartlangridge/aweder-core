<?php

namespace App\Mail\Aweder;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MerchantConfirmationSignUpEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * @var App\Merchant
     */
    public $merchant;

    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $this->merchant = $user->merchants->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mail.register.awe-der-merchant.subject'))
            ->from(config('mail.hello.address'))
            ->view('emails.register.awe-der-merchant');
    }
}
