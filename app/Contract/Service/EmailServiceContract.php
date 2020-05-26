<?php

namespace App\Contract\Service;

use App\Service\EmailService;
use Illuminate\Mail\Mailable;

interface EmailServiceContract
{
    /**
     * Get the to address of the email.
     *
     * @return string|array
     */
    public function getToAddress();

    /**
     * Set the to address of the email
     *
     * @param string|array $toAddress
     *
     * @return EmailService
     */
    public function setToAddress($toAddress): EmailService;

    /**
     * Send the email
     *
     * @param Mailable $mailable
     *
     * @return mixed
     */
    public function sendEmail(Mailable $mailable);
}
