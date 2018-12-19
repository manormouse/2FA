<?php declare(strict_types=1);

namespace App\Domain;

class Retries
{
    private $retries;

    public function __construct(int $retries)
    {
        $this->retries = $retries;
    }

    public function increment(): Retries
    {
        return new self($this->retries() + 1);
    }

    public function isGreaterOrEquals(Retries $retries): bool
    {
        return $this->retries() >= $retries->retries();
    }

    /**
     * @return int
     */
    public function retries(): int
    {
        return $this->retries;
    }
}
