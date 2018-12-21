<?php declare(strict_types=1);

namespace App\Domain;

/**
 * Verification id value object.
 *
 * @author: Pol Pereta Quintana <polpereta@gmail.com>
 * @since: 21/12/18
 */
class VerificationId
{
    /** @var string */
    const UUID_REGEX = '/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/';

    /** @var string */
    private $verificationId;

    /**
     * VerificationId constructor.
     *
     * @param string $verificationId Plain verification id.
     *
     * @throws InvalidVerificationId
     */
    public function __construct(string $verificationId)
    {
        $this->setVerificationId($verificationId);
    }

    /**
     * @param string $verificationId Verification id.
     *
     * @return VerificationId
     *
     * @throws InvalidVerificationId
     */
    private function setVerificationId(string $verificationId): self
    {
        if (!preg_match(self::UUID_REGEX, $verificationId)) {
            throw new InvalidVerificationId("Invalid verification id format for id: {$verificationId}");
        }

        $this->verificationId = $verificationId;

        return $this;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->verificationId;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->verificationId;
    }
}