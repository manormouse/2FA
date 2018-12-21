<?php declare(strict_types=1);

namespace App\Domain;

/**
 * Retries value object.
 *
 * @author: Pol Pereta Quintana <polpereta@gmail.com>
 * @since: 21/12/18
 */
class Retries
{
    /** @var int */
    private $retries;

    /**
     * Retries constructor.
     *
     * @param int $retries Number of retries.
     */
    public function __construct(int $retries)
    {
        $this->retries = $retries;
    }

    /**
     * @return Retries
     */
    public function increment(): Retries
    {
        return new self($this->retries() + 1);
    }

    /**
     * @param Retries $retries
     * @return bool
     */
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
