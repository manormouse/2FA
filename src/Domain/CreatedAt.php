<?php declare(strict_types=1);

namespace App\Domain;

class CreatedAt
{
    private $createdAt;

    public function __construct(\DateTimeImmutable $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function createdAt(): string
    {
        return $this->createdAt->format('YmdHis');
    }
}
