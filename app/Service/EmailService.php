<?php

namespace App\Service;

use App\Contract\Service\EmailServiceContract;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class EmailService implements EmailServiceContract
{
    /**
     * @var array|string $toAddress
     */
    private $toAddress;

    public function getToAddress()
    {
        return $this->toAddress;
    }

    public function setToAddress($toAddress): EmailService
    {
        $this->toAddress = $toAddress;

        return $this;
    }

    public function sendEmail(Mailable $mailable)
    {
        return Mail::to($this->getToAddress())->send($mailable->build());
    }
}
