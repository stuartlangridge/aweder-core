<?php

namespace App\Mail\Merchant;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SignUpEmail extends Mailable
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

        $this->merchant = $user->merchants()->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mail.register.merchant.subject'))
            ->from(config('mail.hello.address'))
            ->replyTo(config('mail.hello.address'))
            ->view('emails.register.merchant');
    }
}
