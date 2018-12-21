<?php declare(strict_types=1);

namespace App\Domain;

/**
 * Creation date value object.
 *
 * @author: Pol Pereta Quintana <polpereta@gmail.com>
 * @since: 21/12/18
 */
class CreatedAt
{
    /** @var \DateTimeImmutable */
    private $createdAt;

    /**
     * CreatedAt constructor.
     *
     * @param \DateTimeImmutable $createdAt Creation date.
     */
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
