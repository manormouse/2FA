<?php declare(strict_types=1);

namespace App\Domain;

/**
 * VerificationCode value objcet.
 *
 * @author: Pol Pereta Quintana <polpereta@gmail.com>
 * @since: 21/12/18
 */
class VerificationCode
{
    /** @var int */
    const CODE_DIGITS = 6;

    /** @var string */
    const CODE_REGEX = '/[0-9A-F]{6}/';

    /** @var string */
    private $code;

    /**
     * VerificationCode constructor.
     *
     * @param string $verificationCode Plain verification code.
     *
     * @throws InvalidVerificationCode
     */
    public function __construct(string $verificationCode)
    {
        $this->setCode($verificationCode);
    }

    /**
     * @param string $code Verification code.
     *
     * @return VerificationCode
     *
     * @throws InvalidVerificationCode
     */
    private function setCode(string $code): self
    {
        if (!preg_match(self::CODE_REGEX, $code)) {
            throw new InvalidVerificationCode("Invalid code format for code: {$code}");
        }

        $this->code = $code;

        return $this;
    }

    /**
     * @param VerificationCode $verificationCode Code.
     *
     * @return bool
     */
    public function isEquals(VerificationCode $verificationCode): bool
    {
        return $this->code() === $verificationCode->code();
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }
}
