<?php declare(strict_types=1);

namespace App\Domain;

/**
 * Verification domain.
 *
 * @author: Pol Pereta Quintana <polpereta@gmail.com>
 * @since: 21/12/18
 */
class Verification
{
    /** @var int */
    const CODE_EXPIRATION_IN_MINUTES = 5;

    /** @var int */
    const MAX_RETRIES = 3;

    /** @var VerificationId */
    private $id;

    /** @var PhoneNumber */
    private $phoneNumber;

    /** @var VerificationCode */
    private $code;

    /** @var Retries */
    private $retries;

    /** @var Verified */
    private $verified;

    /** @var CreatedAt */
    private $createdAt;

    /** @var ExpiresAt */
    private $expiresAt;

    /**
     * Verification constructor.
     *
     * @param VerificationId $aVerificationId Verification id.
     * @param PhoneNumber $anPhoneNumber      Phone number.
     */
    public function __construct(VerificationId $aVerificationId, PhoneNumber $anPhoneNumber)
    {
        $this->id          = $aVerificationId;
        $this->phoneNumber = $anPhoneNumber;
        $this->retries     = new Retries(0);
        $this->verified    = new Verified(false);
        $this->createdAt   = new CreatedAt((new \DateTimeImmutable(CURRENT_DATETIME)));
    }

    /**
     * @return VerificationId
     */
    public function id(): VerificationId
    {
        return $this->id;
    }

    /**
     * @return VerificationCode
     */
    public function code(): VerificationCode
    {
        return $this->code;
    }

    /**
     * @return PhoneNumber
     */
    public function phoneNumber(): PhoneNumber
    {
        return $this->phoneNumber;
    }

    /**
     * @return Verified
     */
    public function verified(): Verified
    {
        return $this->verified;
    }

    /**
     * @return Retries
     */
    public function retries(): Retries
    {
        return $this->retries;
    }

    /**
     * @param VerificationCodeGenerator $verificationCodeGenerator Code generator for verification.
     *
     * @return Verification
     *
     * @throws VerificationCodeGeneratedAlready
     */
    public function generateCode(VerificationCodeGenerator $verificationCodeGenerator): self
    {
        if ($this->code !== null) {
            throw new VerificationCodeGeneratedAlready('Code can be generated only once by verification');
        }

        $this->code = $verificationCodeGenerator->generate();

        $expiredDateTime = (new \DateTimeImmutable(CURRENT_DATETIME))
            ->modify('+' . self::CODE_EXPIRATION_IN_MINUTES . ' minutes');

        $this->expiresAt = new ExpiresAt($expiredDateTime);

        return $this;
    }

    /**
     * @return $this
     * @throws VerificationIsExpired
     */
    private function assertIsNotExpired()
    {
        $currentDatetime = new \DateTimeImmutable(CURRENT_DATETIME);
        $expiredDateTime = new \DateTimeImmutable($this->expiresAt->expiresAt());

        if ($currentDatetime->diff($expiredDateTime)->invert === 1) {
            throw new VerificationIsExpired("Verification {$this->id()->id()} is expired already");
        }

        return $this;
    }

    /**
     * @return $this
     * @throws VerificationVerifiedAlready
     */
    private function assertIsVerified()
    {
        if ($this->verified->isVerified()) {
            throw new VerificationVerifiedAlready("Verification {$this->id()->id()} verified already");
        }

        return $this;
    }

    /**
     * @return $this
     * @throws MaximumNumberOfRetries
     */
    private function assertNumberOfRetries()
    {
        if ($this->retries->isGreaterOrEquals(new Retries(self::MAX_RETRIES))) {
            throw new MaximumNumberOfRetries(
                "Achieved the number of maximum retries for verification {$this->id()->id()}"
            );
        }

        return $this;
    }

    /**
     * @param VerificationCode $aVerificationCode
     * @return $this
     * @throws IncorrectVerificationCode
     */
    private function assertValidCode(VerificationCode $aVerificationCode)
    {
        if (!$this->code->isEquals($aVerificationCode)) {
            $this->retries = $this->retries->increment();

            throw new IncorrectVerificationCode(
                "Code {$aVerificationCode->code()} is incorrect for verification {$this->id->id()}"
            );
        }

        return $this;
    }

    /**
     * @param VerificationCode $aVerificationCode
     * @return $this
     *
     * @throws VerificationVerifiedAlready
     * @throws VerificationIsExpired
     * @throws MaximumNumberOfRetries
     * @throws IncorrectVerificationCode
     */
    public function verify(VerificationCode $aVerificationCode)
    {
        $this
            ->assertIsVerified()
            ->assertIsNotExpired()
            ->assertNumberOfRetries()
            ->assertValidCode($aVerificationCode);

        $this->verified = new Verified(true);
        $this->retries  = $this->retries->increment();

        return $this;
    }
}