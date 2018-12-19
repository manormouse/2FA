<?php declare(strict_types=1);

namespace App\Domain;

class ExpiresAt
{
    private $expiresAt;

    public function __construct(\DateTimeImmutable $expiresAt)
    {
        $this->expiresAt = $expiresAt;
    }

    /**
     * @return string
     */
    public function expiresAt(): string
    {
        return $this->expiresAt->format('YmdHis');
    }
}
