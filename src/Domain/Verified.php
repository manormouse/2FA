<?php declare(strict_types=1);

namespace App\Domain;

class Verified
{
    private $verified;

    public function __construct(bool $verified)
    {
        $this->verified = $verified;
    }

    /**
     * @return bool
     */
    public function verified(): bool
    {
        return $this->verified;
    }

    public function isVerified(): bool
    {
        return $this->verified === true;
    }
}
