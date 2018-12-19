<?php declare(strict_types=1);

namespace App\Domain;

class VerificationCode
{
    private $code;

    public function __construct(string $verificationCode)
    {
        $this->code = $verificationCode;
    }

    public function isEquals(VerificationCode $verificationCode): bool
    {
        return $this->code() === $verificationCode->code();
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }
}
