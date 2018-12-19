<?php declare(strict_types=1);

namespace App\Application;

class CheckVerificationCodeResponse
{
    /** @var string */
    private $phoneNumber;

    /** @var bool */
    private $verified;

    public function __construct(string $phoneNumber, bool $verified)
    {
        $this->phoneNumber = $phoneNumber;
        $this->verified    = $verified;
    }

    public function phoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function verified(): bool
    {
        return $this->verified;
    }
}
