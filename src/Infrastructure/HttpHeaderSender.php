<?php declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\PhoneNumber;
use App\Domain\Sender;
use App\Domain\VerificationCode;
use Symfony\Component\HttpFoundation\Response;

class HttpHeaderSender implements Sender
{
    public function send(PhoneNumber $phoneNumber, VerificationCode $verificationCode)
    {
        $r = Response::create('', 200, ['X-VerificationCode' => $verificationCode->code()]);
        $r->send();

        return true;
    }
}