<?php declare(strict_types=1);

namespace App\Application;

class VerifyPhoneNumberRequest
{
    private $phoneNumber;

    public function __construct(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function phoneNumber(): string
    {
        return $this->phoneNumber;
    }
}
