<?php declare(strict_types=1);

namespace App\Application;

class CheckVerificationCodeRequest
{
    private $verificationId;

    private $code;

    public function __construct(string $verificationId, string $code)
    {
        $this->verificationId = $verificationId;
        $this->code           = $code;
    }

    public function verificationId(): string
    {
        return $this->verificationId;
    }

    public function code(): string
    {
        return $this->code;
    }
}
