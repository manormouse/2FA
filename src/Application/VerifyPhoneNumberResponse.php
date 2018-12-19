<?php declare(strict_types=1);

namespace App\Application;

class VerifyPhoneNumberResponse
{
    /** @var string */
    private $verificationId;

    public function __construct(string $verificationId)
    {
        $this->verificationId = $verificationId;
    }

    /**
     * @return string
     */
    public function verificationId(): string
    {
        return $this->verificationId;
    }
}
