<?php declare(strict_types=1);

namespace App\Domain;

class PhoneNumber
{
    /** @var string */
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