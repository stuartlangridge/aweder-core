<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Mail;

trait InteractsWithEmails
{
    /**
     * Assert whether the mailable was sent
     *
     * @param string $mailableClass
     * @param string $subject
     * @param string $toAddress
     *
     * @return void
     */
    public function assertMailableWasSent(
        string $mailableClass,
        string $subject,
        string $toAddress
    ): void {
        Mail::assertSent($mailableClass, function ($mail) use ($toAddress, $subject) {
            return $mail->hasTo($toAddress) && $mail->subject === $subject;
        });
    }

    /**
     * Assert whether the mailable was not sent
     *
     * @param string $mailableClass
     * @param string $subject
     * @param string $toAddress
     *
     * @return void
     */
    public function assertMailableWasNotSent(
        string $mailableClass,
        string $subject,
        string $toAddress
    ): void {
        Mail::assertNotSent($mailableClass, function ($mail) use ($toAddress, $subject) {
            return $mail->hasTo($toAddress) && $mail->subject === $subject;
        });
    }

    /**
     * @param string $mailableClass
     * @param string $toAddress
     */
    public function assertMailWasSentToEmailAddress(
        string $mailableClass,
        string $toAddress
    ): void {
        Mail::assertSent($mailableClass, function ($mail) use ($toAddress) {
            return $mail->hasTo($toAddress);
        });
    }

    /**
     * @param string $mailableClass
     * @param string $toAddress
     */
    public function assertMailWasNotSentToEmailAddress(
        string $mailableClass,
        string $toAddress
    ): void {
        Mail::assertNotSent($mailableClass, function ($mail) use ($toAddress) {
            return $mail->hasTo($toAddress);
        });
    }
}
