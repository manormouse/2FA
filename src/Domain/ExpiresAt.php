<?php declare(strict_types=1);

namespace App\Domain;

/**
 * Expiration date value object.
 *
 * @author: Pol Pereta Quintana <polpereta@gmail.com>
 * @since: 21/12/18
 */
class ExpiresAt
{
    /** @var \DateTimeImmutable */
    private $expiresAt;

    /**
     * ExpiresAt constructor.
     *
     * @param \DateTimeImmutable $expiresAt Expiration date.
     */
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
