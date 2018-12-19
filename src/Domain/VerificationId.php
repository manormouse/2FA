<?php declare(strict_types=1);

namespace App\Domain;

class VerificationId
{
    private $verificationId;

    public function __construct($verificationId)
    {
        $this->verificationId = $verificationId;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->verificationId;
    }

    public function __toString()
    {
        return $this->verificationId;
    }
}