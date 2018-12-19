<?php declare(strict_types=1);

namespace App\Domain;

interface Sender
{
    public function send(PhoneNumber $phoneNumber, VerificationCode $verificationCode);
}
