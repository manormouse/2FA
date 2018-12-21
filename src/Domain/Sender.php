<?php declare(strict_types=1);

namespace App\Domain;

/**
 * Code sender interface.
 */
interface Sender
{
    /**
     * @param PhoneNumber      $phoneNumber      Phone number to be sent.
     * @param VerificationCode $verificationCode Verification code to send.
     */
    public function send(PhoneNumber $phoneNumber, VerificationCode $verificationCode);
}
