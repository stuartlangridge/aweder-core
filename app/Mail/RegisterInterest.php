<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterInterest extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var string
     */
    public $business;

    /**
     * @var string
     */
    public $businessEmail;

    /**
     * @var string
     */
    public $location;

    /**
     * @var string
     */
    public $businessType;

    /**
     * Create a new message instance.
     *
     * @param array $requestData
     */
    public function __construct(array $requestData = [])
    {
        $this->business = $requestData['business'];

        $this->businessEmail = $requestData['email'];

        $this->location = $requestData['location'];

        $this->businessType = $requestData['business_type'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('A Store has Registered interest in Awe-der')
            ->from(config('mail.hello.address'), config('mail.hello.address'))
            ->replyTo(config('mail.hello.address'))
            ->view('emails.stores.register-interest');
    }
}
