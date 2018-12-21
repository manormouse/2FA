<?php declare(strict_types=1);

namespace App\Domain;

/**
 * Verified value object.
 *
 * @author: Pol Pereta Quintana <polpereta@gmail.com>
 * @since: 21/12/18
 */
class Verified
{
    /** @var bool */
    private $verified;

    /**
     * Verified constructor.
     *
     * @param bool $verified Is verified.
     */
    public function __construct(bool $verified)
    {
        $this->verified = $verified;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verified === true;
    }
}
